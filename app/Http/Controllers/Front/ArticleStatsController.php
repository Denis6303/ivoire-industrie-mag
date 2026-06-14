<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ArticleStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleStatsController extends Controller
{
    private const ALLOWED_EVENTS = [
        'share_facebook', 'share_linkedin', 'share_twitter', 'share_whatsapp', 'share_copy',
        'scroll_25', 'scroll_50', 'scroll_75', 'scroll_100',
        'qualified_read', 'time_on_page', 'bounce',
        'click_internal', 'click_external', 'click_related',
        'click_cover', 'click_secondary', 'click_newsletter', 'click_jobs', 'click_companies',
        'newsletter_signup', 'web_vitals',
    ];

    public function __construct(private ArticleStatsService $stats) {}

    public function store(Request $request, string $locale, string $slug): JsonResponse
    {
        $article = Article::query()
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail(['id', 'slug']);

        $validated = $request->validate([
            'event' => ['required', 'string', 'in:'.implode(',', self::ALLOWED_EVENTS)],
            'payload' => ['nullable', 'array'],
        ]);

        $this->stats->recordEvent(
            $article,
            $validated['event'],
            $request,
            $validated['payload'] ?? null
        );

        return response()->json(['ok' => true]);
    }
}
