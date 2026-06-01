<?php

namespace App\Services;

use App\Models\SiteVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SiteVisitorTracker
{
    public const COOKIE_NAME = 'ivm_vid';

    private const BOT_PATTERNS = [
        'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'curl',
        'wget', 'python', 'java/', 'libwww', 'httpclient',
        'facebookexternalhit', 'linkedinbot', 'twitterbot', 'whatsapp',
        'applebot', 'yandex', 'baidu', 'duckduckbot', 'semrush',
        'ahrefsbot', 'mj12bot', 'dotbot', 'petalbot',
    ];

    public function track(Request $request, Response $response): Response
    {
        if (! $this->shouldTrack($request)) {
            return $response;
        }

        $visitorKey = $this->resolveVisitorKey($request);
        $now = now();

        $visitor = SiteVisitor::query()->firstOrCreate(
            ['visitor_key' => $visitorKey],
            [
                'first_seen_at' => $now,
                'last_seen_at' => $now,
                'page_views' => 1,
            ]
        );

        if (! $visitor->wasRecentlyCreated) {
            $visitor->forceFill([
                'last_seen_at' => $now,
                'page_views' => $visitor->page_views + 1,
            ])->saveQuietly();
        }

        if ($request->cookie(self::COOKIE_NAME) === $visitorKey) {
            return $response;
        }

        return $response->withCookie(cookie(
            self::COOKIE_NAME,
            $visitorKey,
            60 * 24 * 365,
            '/',
            null,
            $request->isSecure(),
            true,
            false,
            'Lax'
        ));
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if (! Schema::hasTable('site_visitors')) {
            return false;
        }

        if ($request->is('admin/*') || $request->is('admin')) {
            return false;
        }

        if ($request->ajax() || in_array($request->header('Sec-Purpose'), ['prefetch', 'prerender'], true)) {
            return false;
        }

        $userAgent = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return false;
            }
        }

        return true;
    }

    private function resolveVisitorKey(Request $request): string
    {
        $fromCookie = $request->cookie(self::COOKIE_NAME);

        if (is_string($fromCookie) && Str::isUuid($fromCookie)) {
            return $fromCookie;
        }

        return (string) Str::uuid();
    }
}
