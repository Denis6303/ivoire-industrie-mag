<?php

namespace App\Services;

use Illuminate\Http\Request;

class VisitorContext
{
    private const OG_CRAWLER_PATTERNS = [
        'facebookexternalhit', 'linkedinbot', 'twitterbot', 'whatsapp',
        'slackbot', 'telegrambot', 'discordbot', 'pinterest',
    ];

    private const BOT_PATTERNS = [
        'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'curl',
        'wget', 'python', 'java/', 'libwww', 'httpclient',
        'applebot', 'yandex', 'baidu', 'duckduckbot', 'semrush',
        'ahrefsbot', 'mj12bot', 'dotbot', 'petalbot',
    ];

    public function __construct(private Request $request) {}

    public static function from(Request $request): self
    {
        return new self($request);
    }

    public function isOgCrawler(): bool
    {
        $ua = strtolower($this->request->userAgent() ?? '');

        foreach (self::OG_CRAWLER_PATTERNS as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function isBot(): bool
    {
        if ($this->isOgCrawler()) {
            return true;
        }

        $ua = strtolower($this->request->userAgent() ?? '');
        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function shouldTrackHuman(): bool
    {
        if (! $this->request->isMethod('GET') && ! $this->request->isMethod('POST')) {
            return false;
        }

        if ($this->isBot()) {
            return false;
        }

        if (auth()->check()) {
            return false;
        }

        return true;
    }

    public function visitorKey(): ?string
    {
        $cookie = $this->request->cookie(SiteVisitorTracker::COOKIE_NAME);

        return is_string($cookie) && strlen($cookie) >= 16 ? $cookie : null;
    }

    public function locale(): string
    {
        return app()->getLocale();
    }

    public function referrer(): ?string
    {
        $referrer = $this->request->headers->get('referer');

        return is_string($referrer) && $referrer !== '' ? mb_substr($referrer, 0, 500) : null;
    }

    public function utmSource(): ?string
    {
        return $this->utm('utm_source');
    }

    public function utmMedium(): ?string
    {
        return $this->utm('utm_medium');
    }

    public function utmCampaign(): ?string
    {
        return $this->utm('utm_campaign');
    }

    public function sourceType(): string
    {
        if ($this->utmSource()) {
            return 'campaign';
        }

        $referrer = $this->referrer();
        if ($referrer === null) {
            return 'direct';
        }

        $host = strtolower(parse_url($referrer, PHP_URL_HOST) ?? '');
        $path = parse_url($referrer, PHP_URL_PATH) ?? '';
        $appHost = strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? '');

        if ($host !== '' && ($host === $appHost || str_ends_with($host, '.'.$appHost))) {
            if (preg_match('#/(fr|en)/?$#', $path)) {
                return 'homepage';
            }
            if (str_contains($path, '/categories/')) {
                return 'category';
            }
            if (str_contains($path, '/newsletter')) {
                return 'newsletter';
            }

            return 'internal';
        }

        if (preg_match('/google|bing|yahoo|duckduckgo|baidu|yandex|ecosia|qwant/', $host)) {
            return 'organic';
        }

        if (preg_match('/facebook|fb\.|instagram|linkedin|twitter|t\.co|x\.com|whatsapp|tiktok|telegram/', $host)) {
            return 'social';
        }

        if (preg_match('/mail|newsletter|outlook|gmail|sendgrid|mailchimp/', $host)) {
            return 'email';
        }

        return 'referral';
    }

    public function referrerHost(): ?string
    {
        $referrer = $this->referrer();
        if ($referrer === null) {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? mb_substr(strtolower($host), 0, 255) : null;
    }

    public function deviceType(): string
    {
        $ua = strtolower($this->request->userAgent() ?? '');

        if (preg_match('/ipad|tablet|kindle|playbook|silk/', $ua)) {
            return 'tablet';
        }

        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini|webos/', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    public function browser(): string
    {
        $ua = $this->request->userAgent() ?? '';

        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'Chrome/') && ! str_contains($ua, 'Edg/') => 'Chrome',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Safari/') && ! str_contains($ua, 'Chrome/') => 'Safari',
            str_contains($ua, 'Opera') || str_contains($ua, 'OPR/') => 'Opera',
            default => 'Autre',
        };
    }

    public function os(): string
    {
        $ua = $this->request->userAgent() ?? '';

        return match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS') || str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Autre',
        };
    }

    public function countryCode(): ?string
    {
        foreach (['CF-IPCountry', 'X-Country-Code', 'X-AppEngine-Country'] as $header) {
            $value = $this->request->headers->get($header);
            if (is_string($value) && preg_match('/^[A-Z]{2}$/', strtoupper($value))) {
                return strtoupper($value);
            }
        }

        return null;
    }

    /** @return array<string, mixed> */
    public function snapshot(): array
    {
        return [
            'referrer' => $this->referrer(),
            'source_type' => $this->sourceType(),
            'utm_source' => $this->utmSource(),
            'utm_medium' => $this->utmMedium(),
            'utm_campaign' => $this->utmCampaign(),
            'locale' => $this->locale(),
            'device_type' => $this->deviceType(),
            'browser' => $this->browser(),
            'os' => $this->os(),
            'country_code' => $this->countryCode(),
        ];
    }

    private function utm(string $key): ?string
    {
        $value = $this->request->query($key);

        return is_string($value) && $value !== '' ? mb_substr($value, 0, 120) : null;
    }
}
