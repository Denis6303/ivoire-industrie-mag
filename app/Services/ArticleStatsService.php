<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleStat;
use App\Models\ArticleStatDaily;
use App\Models\ArticleStatEvent;
use App\Models\ArticleStatReferrer;
use App\Models\ArticleViewSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArticleStatsService
{
    private const SESSION_DEDUP_HOURS = 4;

    private const SHARE_EVENTS = [
        'share_facebook' => 'shares_facebook',
        'share_linkedin' => 'shares_linkedin',
        'share_twitter' => 'shares_twitter',
        'share_whatsapp' => 'shares_whatsapp',
        'share_copy' => 'shares_copy',
    ];

    private const SCROLL_EVENTS = [
        'scroll_25' => 'scroll_25',
        'scroll_50' => 'scroll_50',
        'scroll_75' => 'scroll_75',
        'scroll_100' => 'scroll_100',
    ];

    private const CLICK_EVENTS = [
        'click_internal' => 'clicks_internal_links',
        'click_external' => 'clicks_external_links',
        'click_related' => 'clicks_related',
        'click_cover' => 'clicks_cover_image',
        'click_secondary' => 'clicks_secondary_image',
        'click_newsletter' => 'clicks_newsletter',
        'click_jobs' => 'clicks_jobs',
        'click_companies' => 'clicks_companies',
    ];

    public function tablesReady(): bool
    {
        return Schema::hasTable('article_stats');
    }

    public function recordPageView(Article $article, Request $request): void
    {
        if (! $this->tablesReady()) {
            return;
        }

        $context = VisitorContext::from($request);

        if ($context->isOgCrawler()) {
            $this->incrementStat($article->id, ['og_crawler_hits' => 1]);

            return;
        }

        if (! $context->shouldTrackHuman()) {
            return;
        }

        $slug = $article->slug;
        $sessionKey = 'viewed_article_'.md5($slug);
        if ($request->session()->has($sessionKey)) {
            return;
        }

        $now = now();
        $visitorKey = $context->visitorKey() ?? (string) Str::uuid();
        $snapshot = $context->snapshot();
        $sourceType = $snapshot['source_type'];
        $locale = $snapshot['locale'];
        $device = $snapshot['device_type'];

        $existingSession = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->where('visitor_key', $visitorKey)
            ->first();

        $isReturning = $existingSession !== null;

        DB::transaction(function () use ($article, $visitorKey, $snapshot, $sourceType, $locale, $device, $now, $existingSession, $isReturning) {
            $increments = [
                'views_total' => 1,
                'last_view_at' => $now,
            ];

            if (! $isReturning) {
                $increments['views_unique'] = 1;
            } else {
                $increments['views_returning'] = 1;
            }

            $sourceColumn = match ($sourceType) {
                'organic' => 'views_organic',
                'social' => 'views_social',
                'direct' => 'views_direct',
                'referral' => 'views_referral',
                'campaign' => 'views_campaign',
                'homepage' => 'views_homepage',
                'category' => 'views_category',
                'internal' => 'views_internal',
                'newsletter' => 'views_newsletter',
                default => null,
            };

            if ($sourceColumn) {
                $increments[$sourceColumn] = 1;
            }

            if ($locale === 'fr') {
                $increments['views_fr'] = 1;
            } elseif ($locale === 'en') {
                $increments['views_en'] = 1;
            }

            $deviceColumn = match ($device) {
                'mobile' => 'views_mobile',
                'tablet' => 'views_tablet',
                'desktop' => 'views_desktop',
                default => null,
            };

            if ($deviceColumn) {
                $increments[$deviceColumn] = 1;
            }

            if ($existingSession === null) {
                $increments['first_view_at'] = $now;
            }

            $this->incrementStat($article->id, $increments);

            Article::whereKey($article->id)
                ->toBase()
                ->update(['view_count' => DB::raw('view_count + 1')]);

            if ($existingSession) {
                $existingSession->update([
                    'view_count' => $existingSession->view_count + 1,
                    'last_viewed_at' => $now,
                ]);
            } else {
                ArticleViewSession::create([
                    'article_id' => $article->id,
                    'visitor_key' => $visitorKey,
                    'view_count' => 1,
                    'first_viewed_at' => $now,
                    'last_viewed_at' => $now,
                    'referrer' => $snapshot['referrer'],
                    'source_type' => $sourceType,
                    'utm_source' => $snapshot['utm_source'],
                    'utm_medium' => $snapshot['utm_medium'],
                    'utm_campaign' => $snapshot['utm_campaign'],
                    'locale' => $locale,
                    'device_type' => $device,
                    'browser' => $snapshot['browser'],
                    'os' => $snapshot['os'],
                    'country_code' => $snapshot['country_code'],
                ]);
            }

            $this->incrementDaily($article->id, [
                'views' => 1,
                'unique_visitors' => $isReturning ? 0 : 1,
                'views_fr' => $locale === 'fr' ? 1 : 0,
                'views_en' => $locale === 'en' ? 1 : 0,
            ]);

            if ($host = $context->referrerHost()) {
                $referrer = ArticleStatReferrer::query()->firstOrCreate(
                    ['article_id' => $article->id, 'referrer_host' => $host],
                    ['hit_count' => 0]
                );
                $referrer->increment('hit_count');
            }

            $this->logEvent($article->id, $visitorKey, 'view', null, $snapshot);
            $this->refreshPeakViews($article->id);
        });

        $request->session()->put($sessionKey, true);
        $request->session()->push('_view_ttls', [$sessionKey => now()->addHours(self::SESSION_DEDUP_HOURS)->timestamp]);
    }

    public function recordEvent(Article $article, string $eventType, Request $request, ?array $payload = null): void
    {
        if (! $this->tablesReady()) {
            return;
        }

        $context = VisitorContext::from($request);
        if ($context->isBot() || auth()->check()) {
            return;
        }

        $visitorKey = $context->visitorKey();
        $snapshot = $context->snapshot();
        $increments = [];

        if (isset(self::SHARE_EVENTS[$eventType])) {
            $increments[self::SHARE_EVENTS[$eventType]] = 1;
            $increments['shares_total'] = 1;
            $this->incrementDaily($article->id, ['shares' => 1]);
        }

        if (isset(self::SCROLL_EVENTS[$eventType])) {
            $increments[self::SCROLL_EVENTS[$eventType]] = 1;
            $depth = (int) str_replace('scroll_', '', $eventType);
            if ($visitorKey) {
                ArticleViewSession::query()
                    ->where('article_id', $article->id)
                    ->where('visitor_key', $visitorKey)
                    ->where('max_scroll_depth', '<', $depth)
                    ->update(['max_scroll_depth' => $depth]);
            }
        }

        if (isset(self::CLICK_EVENTS[$eventType])) {
            $increments[self::CLICK_EVENTS[$eventType]] = 1;
        }

        if ($eventType === 'qualified_read') {
            $increments['qualified_reads'] = 1;
            $this->incrementDaily($article->id, ['qualified_reads' => 1]);
            if ($visitorKey) {
                ArticleViewSession::query()
                    ->where('article_id', $article->id)
                    ->where('visitor_key', $visitorKey)
                    ->update(['is_qualified' => true]);
            }
        }

        if ($eventType === 'time_on_page') {
            $seconds = max(0, min(3600, (int) ($payload['seconds'] ?? 0)));
            if ($seconds > 0) {
                $increments['time_on_page_total_seconds'] = $seconds;
                $increments['time_on_page_samples'] = 1;
                $this->incrementDaily($article->id, [
                    'time_on_page_total_seconds' => $seconds,
                    'time_on_page_samples' => 1,
                ]);
                if ($visitorKey) {
                    ArticleViewSession::query()
                        ->where('article_id', $article->id)
                        ->where('visitor_key', $visitorKey)
                        ->update(['total_time_seconds' => DB::raw('total_time_seconds + '.$seconds)]);
                }
            }
        }

        if ($eventType === 'bounce') {
            $increments['bounces'] = 1;
        }

        if ($eventType === 'newsletter_signup') {
            $increments['newsletter_signups'] = 1;
        }

        if ($increments !== []) {
            $this->incrementStat($article->id, $increments);
        }

        $this->logEvent($article->id, $visitorKey, $eventType, $payload, $snapshot);
    }

    /** @return array<string, mixed> */
    public function buildReport(Article $article): array
    {
        $stat = $this->ensureStat($article);
        $publishedAt = $article->published_at;
        $comments = $article->comments()->withTrashed(false);
        $approvedComments = (clone $comments)->where('is_approved', true);
        $pendingComments = (clone $comments)->where('is_approved', false);
        $replies = (clone $comments)->whereNotNull('parent_id');

        $daily = ArticleStatDaily::query()
            ->where('article_id', $article->id)
            ->where('date', '>=', now()->subDays(29)->toDateString())
            ->orderBy('date')
            ->get();

        $referrers = ArticleStatReferrer::query()
            ->where('article_id', $article->id)
            ->orderByDesc('hit_count')
            ->limit(10)
            ->get();

        $devices = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        $browsers = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'browser');

        $countries = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->whereNotNull('country_code')
            ->select('country_code', DB::raw('COUNT(*) as total'))
            ->groupBy('country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'country_code');

        $sources = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->select('source_type', DB::raw('COUNT(*) as total'))
            ->groupBy('source_type')
            ->pluck('total', 'source_type');

        $locales = ArticleViewSession::query()
            ->where('article_id', $article->id)
            ->select('locale', DB::raw('COUNT(*) as total'))
            ->groupBy('locale')
            ->pluck('total', 'locale');

        $webVitals = ArticleStatEvent::query()
            ->where('article_id', $article->id)
            ->where('event_type', 'web_vitals')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        $avgLcp = $this->averagePayloadMetric($webVitals, 'lcp');
        $avgCls = $this->averagePayloadMetric($webVitals, 'cls');
        $avgInp = $this->averagePayloadMetric($webVitals, 'inp');

        $categoryAvgViews = 0;
        if ($article->category_id) {
            $categoryAvgViews = (int) round(
                ArticleStat::query()
                    ->whereHas('article', fn ($q) => $q->where('category_id', $article->category_id)->where('status', 'published'))
                    ->avg('views_total') ?? 0
            );
        }

        $authorAvgViews = (int) round(
            ArticleStat::query()
                ->whereHas('article', fn ($q) => $q->where('author_id', $article->author_id)->where('status', 'published'))
                ->avg('views_total') ?? 0
        );

        $typeAvgViews = (int) round(
            ArticleStat::query()
                ->whereHas('article', fn ($q) => $q->where('type', $article->type)->where('status', 'published'))
                ->avg('views_total') ?? 0
        );

        $siteRank = ArticleStat::query()
            ->where('views_total', '>', $stat->views_total)
            ->count() + 1;

        $views24h = ArticleStatDaily::query()
            ->where('article_id', $article->id)
            ->where('date', '>=', $publishedAt?->toDateString())
            ->where('date', '<=', $publishedAt?->copy()->addDay()->toDateString())
            ->sum('views');

        $views7d = ArticleStatDaily::query()
            ->where('article_id', $article->id)
            ->where('date', '>=', $publishedAt?->toDateString())
            ->where('date', '<=', $publishedAt?->copy()->addDays(7)->toDateString())
            ->sum('views');

        $views30d = ArticleStatDaily::query()
            ->where('article_id', $article->id)
            ->where('date', '>=', $publishedAt?->toDateString())
            ->where('date', '<=', $publishedAt?->copy()->addDays(30)->toDateString())
            ->sum('views');

        $stillReadAfter30 = $publishedAt && $publishedAt->lt(now()->subDays(30))
            ? ArticleStatDaily::query()
                ->where('article_id', $article->id)
                ->where('date', '>=', now()->subDays(30)->toDateString())
                ->sum('views')
            : null;

        $firstComment = $comments->orderBy('created_at')->first();
        $timeToFirstComment = ($publishedAt && $firstComment)
            ? $publishedAt->diffInHours($firstComment->created_at)
            : null;

        $guestComments = (clone $comments)->whereNull('user_id')->count();
        $userComments = (clone $comments)->whereNotNull('user_id')->count();

        $wordCount = str_word_count(strip_tags(($article->excerpt ?? '').' '.($article->content ?? '')));

        $avgTime = $stat->time_on_page_samples > 0
            ? (int) round($stat->time_on_page_total_seconds / $stat->time_on_page_samples)
            : 0;

        $shareRate = $stat->views_unique > 0
            ? round(($stat->shares_total / $stat->views_unique) * 100, 2)
            : 0;

        $commentRate = $stat->views_total > 0
            ? round(($approvedComments->count() / $stat->views_total) * 100, 2)
            : 0;

        $engagementRate = $stat->views_total > 0
            ? round((($stat->scroll_75 + $stat->shares_total + $approvedComments->count()) / $stat->views_total) * 100, 2)
            : 0;

        $performanceScore = min(100, (int) round(
            ($stat->views_total > 0 ? min(40, log10(max(1, $stat->views_total)) * 10) : 0)
            + ($shareRate * 0.2)
            + ($commentRate * 0.2)
            + ($stat->qualified_reads > 0 && $stat->views_total > 0 ? min(20, ($stat->qualified_reads / $stat->views_total) * 100) : 0)
            + ($avgTime > 30 ? min(20, $avgTime / 6) : 0)
        ));

        return [
            'stat' => $stat,
            'daily' => $daily,
            'referrers' => $referrers,
            'devices' => $devices,
            'browsers' => $browsers,
            'countries' => $countries,
            'sources' => $sources,
            'locales' => $locales,
            'comments' => [
                'total' => $comments->count(),
                'approved' => $approvedComments->count(),
                'pending' => $pendingComments->count(),
                'replies' => $replies->count(),
                'guest' => $guestComments,
                'registered' => $userComments,
                'rate' => $commentRate,
                'time_to_first_hours' => $timeToFirstComment,
            ],
            'editorial' => [
                'category_avg_views' => $categoryAvgViews,
                'author_avg_views' => $authorAvgViews,
                'type_avg_views' => $typeAvgViews,
                'site_rank' => $siteRank,
                'views_24h' => (int) $views24h,
                'views_7d' => (int) $views7d,
                'views_30d' => (int) $views30d,
                'still_read_after_30d' => $stillReadAfter30,
                'performance_score' => $performanceScore,
                'word_count' => $wordCount,
                'has_cover' => ! empty($article->cover_image),
                'tags_count' => $article->tags()->count(),
                'has_meta' => ! empty($article->meta_title) && ! empty($article->meta_description),
            ],
            'engagement' => [
                'avg_time_seconds' => $avgTime,
                'share_rate' => $shareRate,
                'engagement_rate' => $engagementRate,
                'scroll_25_rate' => $this->rate($stat->scroll_25, $stat->views_total),
                'scroll_50_rate' => $this->rate($stat->scroll_50, $stat->views_total),
                'scroll_75_rate' => $this->rate($stat->scroll_75, $stat->views_total),
                'scroll_100_rate' => $this->rate($stat->scroll_100, $stat->views_total),
                'qualified_rate' => $this->rate($stat->qualified_reads, $stat->views_total),
            ],
            'web_vitals' => [
                'lcp_ms' => $avgLcp,
                'cls' => $avgCls,
                'inp_ms' => $avgInp,
                'samples' => $webVitals->count(),
            ],
            'seo' => [
                'search_console_connected' => false,
                'impressions' => null,
                'ctr' => null,
                'avg_position' => null,
            ],
        ];
    }

    public function ensureStat(Article $article): ArticleStat
    {
        return ArticleStat::query()->firstOrCreate(
            ['article_id' => $article->id],
            [
                'views_total' => $article->view_count,
                'first_view_at' => $article->published_at,
            ]
        );
    }

    /** @param array<string, int|float|string|\Illuminate\Support\Carbon|null> $fields */
    private function incrementStat(int $articleId, array $fields): void
    {
        $article = Article::query()->find($articleId, ['id', 'view_count', 'published_at']);
        if (! $article) {
            return;
        }

        ArticleStat::query()->firstOrCreate(
            ['article_id' => $articleId],
            [
                'views_total' => $article->view_count,
                'first_view_at' => $article->published_at,
            ]
        );

        $updates = [];
        foreach ($fields as $column => $value) {
            if (in_array($column, ['first_view_at', 'last_view_at', 'peak_views_date'], true)) {
                continue;
            }
            if (is_numeric($value)) {
                $updates[$column] = DB::raw($column.' + '.(int) $value);
            }
        }

        if ($updates !== []) {
            ArticleStat::query()->where('article_id', $articleId)->update($updates);
        }

        $direct = array_intersect_key($fields, array_flip(['first_view_at', 'last_view_at', 'peak_views_date']));
        if ($direct !== []) {
            $stat = ArticleStat::query()->where('article_id', $articleId)->first();
            if ($stat) {
                if (isset($direct['first_view_at']) && $stat->first_view_at === null) {
                    $stat->first_view_at = $direct['first_view_at'];
                }
                if (isset($direct['last_view_at'])) {
                    $stat->last_view_at = $direct['last_view_at'];
                }
                $stat->saveQuietly();
            }
        }
    }

    /** @param array<string, int> $fields */
    private function incrementDaily(int $articleId, array $fields): void
    {
        $date = now()->toDateString();
        $daily = ArticleStatDaily::query()->firstOrCreate(
            ['article_id' => $articleId, 'date' => $date],
            ['views' => 0, 'unique_visitors' => 0]
        );

        $updates = [];
        foreach ($fields as $column => $value) {
            if ($value > 0) {
                $updates[$column] = DB::raw($column.' + '.(int) $value);
            }
        }

        if ($updates !== []) {
            ArticleStatDaily::query()
                ->whereKey($daily->id)
                ->update($updates);
        }
    }

    private function refreshPeakViews(int $articleId): void
    {
        $todayViews = ArticleStatDaily::query()
            ->where('article_id', $articleId)
            ->where('date', now()->toDateString())
            ->value('views') ?? 0;

        $stat = ArticleStat::query()->where('article_id', $articleId)->first();
        if ($stat && $todayViews > $stat->peak_views_count) {
            $stat->update([
                'peak_views_count' => $todayViews,
                'peak_views_date' => now()->toDateString(),
            ]);
        }
    }

    /** @param array<string, mixed>|null $payload @param array<string, mixed> $snapshot */
    private function logEvent(int $articleId, ?string $visitorKey, string $eventType, ?array $payload, array $snapshot): void
    {
        ArticleStatEvent::create([
            'article_id' => $articleId,
            'visitor_key' => $visitorKey,
            'event_type' => $eventType,
            'payload' => $payload,
            'referrer' => $snapshot['referrer'] ?? null,
            'source_type' => $snapshot['source_type'] ?? null,
            'utm_source' => $snapshot['utm_source'] ?? null,
            'utm_medium' => $snapshot['utm_medium'] ?? null,
            'utm_campaign' => $snapshot['utm_campaign'] ?? null,
            'locale' => $snapshot['locale'] ?? null,
            'device_type' => $snapshot['device_type'] ?? null,
            'browser' => $snapshot['browser'] ?? null,
            'os' => $snapshot['os'] ?? null,
            'country_code' => $snapshot['country_code'] ?? null,
            'created_at' => now(),
        ]);
    }

    private function rate(int $part, int $total): float
    {
        return $total > 0 ? round(($part / $total) * 100, 2) : 0.0;
    }

    /** @param \Illuminate\Support\Collection<int, ArticleStatEvent> $events */
    private function averagePayloadMetric($events, string $key): ?float
    {
        $values = $events
            ->map(fn (ArticleStatEvent $event) => $event->payload[$key] ?? null)
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (float) $value);

        return $values->isNotEmpty() ? round($values->avg(), 2) : null;
    }
}
