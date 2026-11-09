<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    private const CHUNK_SIZE = 900;

    public ?string $lastError = null;

    private static int $lastRequestMs = 0;

    /** @var array<string, bool> */
    private static array $disabledProviders = [];

    public function translate(string $text, string $source = 'fr', string $target = 'en'): ?string
    {
        $text = trim($text);
        $this->lastError = null;

        if ($text === '') {
            $this->lastError = 'texte vide';

            return null;
        }

        if ($source === $target) {
            return $text;
        }

        $cacheKey = 'auto_translation:v4:'.md5($source.'|'.$target.'|'.$text);
        $cached = Cache::get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        try {
            $translated = $this->translateChunks($text, $source, $target);
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::warning('Auto translation failed', ['message' => $e->getMessage()]);

            return null;
        }

        if ($translated === null || trim($translated) === '') {
            $this->lastError ??= 'traduction vide';

            return null;
        }

        Cache::put($cacheKey, $translated, now()->addDays(30));

        return $translated;
    }

    private function translateChunks(string $text, string $source, string $target): ?string
    {
        $out = '';
        foreach ($this->splitChunks($text) as $chunk) {
            $part = $this->translateChunk($chunk, $source, $target);
            if ($part === null) {
                return null;
            }
            $out .= $part;
        }

        return $out;
    }

    private function translateChunk(string $text, string $source, string $target): ?string
    {
        $translated = $this->translateWithGoogleChrome($text, $source, $target);
        if ($translated !== null) {
            return $translated;
        }

        $translated = $this->translateWithMyMemory($text, $source, $target);
        if ($translated !== null) {
            return $translated;
        }

        return $this->translateWithGoogleGtx($text, $source, $target);
    }

    private function splitChunks(string $text): array
    {
        if (mb_strlen($text) <= self::CHUNK_SIZE) {
            return [$text];
        }

        $parts = preg_split('/(?<=<\/p>|<\/h[1-6]>|\. |\n\n)/u', $text) ?: [$text];
        $chunks = [];
        $current = '';

        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            if ($current !== '' && mb_strlen($current.$part) > self::CHUNK_SIZE) {
                $chunks[] = $current;
                $current = $part;
            } else {
                $current .= $part;
            }
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        $normalized = [];
        foreach ($chunks as $chunk) {
            if (mb_strlen($chunk) <= self::CHUNK_SIZE) {
                $normalized[] = $chunk;
                continue;
            }
            $length = mb_strlen($chunk);
            for ($i = 0; $i < $length; $i += self::CHUNK_SIZE) {
                $normalized[] = mb_substr($chunk, $i, self::CHUNK_SIZE);
            }
        }

        return $normalized ?: [$text];
    }

    private function httpClient()
    {
        return Http::withOptions([
            'verify' => (bool) config('translation.verify_ssl', false),
        ])
            ->connectTimeout(4)
            ->timeout((int) config('translation.timeout_seconds', 6))
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => '*/*',
            ]);
    }

    private function throttle(): void
    {
        $delay = max(0, (int) config('translation.request_delay_ms', 150));
        if ($delay === 0) {
            return;
        }

        $now = (int) floor(microtime(true) * 1000);
        $wait = $delay - ($now - self::$lastRequestMs);
        if (self::$lastRequestMs > 0 && $wait > 0) {
            usleep($wait * 1000);
        }
        self::$lastRequestMs = (int) floor(microtime(true) * 1000);
    }

    private function disableProvider(string $name, string $reason): void
    {
        self::$disabledProviders[$name] = true;
        $this->lastError = $reason;
        Log::warning('Translation provider disabled for this process', [
            'provider' => $name,
            'reason' => $reason,
        ]);
    }

    private function translateWithGoogleChrome(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['chrome'])) {
            return null;
        }

        $this->throttle();
        $url = 'https://clients5.google.com/translate_a/t';
        $params = [
            'client' => 'dict-chrome-ex',
            'sl' => $source,
            'tl' => $target,
            'q' => $text,
        ];

        try {
            $response = mb_strlen($text) > 700
                ? $this->httpClient()->asForm()->post($url, $params)
                : $this->httpClient()->get($url, $params);
        } catch (\Throwable $e) {
            $this->disableProvider('chrome', 'Chrome timeout: '.$e->getMessage());

            return null;
        }

        $status = $response->status();
        if (in_array($status, [429, 403, 503], true)) {
            $this->disableProvider('chrome', 'Chrome HTTP '.$status);

            return null;
        }

        if (! $response->ok()) {
            $this->lastError = 'Chrome HTTP '.$status;

            return null;
        }

        $parsed = $this->parseChromePayload($response->body());
        if ($parsed === null) {
            $this->lastError = 'réponse Chrome illisible';
        }

        return $parsed;
    }

    private function parseChromePayload(string $body): ?string
    {
        $payload = json_decode(trim($body), true);
        if (! is_array($payload) || $payload === []) {
            return null;
        }

        if (isset($payload[0]) && is_string($payload[0])) {
            $translated = implode('', array_filter($payload, 'is_string'));

            return trim($translated) !== '' ? $translated : null;
        }

        return $this->parseGtxPayload($payload);
    }

    private function translateWithMyMemory(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['mymemory'])) {
            return null;
        }

        if (mb_strlen($text) > 450) {
            return null;
        }

        $this->throttle();
        $query = [
            'q' => $text,
            'langpair' => $source.'|'.$target,
        ];
        $email = config('mail.from.address');
        if (is_string($email) && $email !== '') {
            $query['de'] = $email;
        }

        try {
            $response = $this->httpClient()->get('https://api.mymemory.translated.net/get', $query);
        } catch (\Throwable $e) {
            $this->disableProvider('mymemory', 'MyMemory timeout: '.$e->getMessage());

            return null;
        }

        if (! $response->ok()) {
            if (in_array($response->status(), [429, 403], true)) {
                $this->disableProvider('mymemory', 'MyMemory HTTP '.$response->status());
            } else {
                $this->lastError = 'MyMemory HTTP '.$response->status();
            }

            return null;
        }

        $translated = (string) ($response->json('responseData.translatedText') ?? '');
        if (stripos($translated, 'MYMEMORY WARNING') !== false) {
            $this->disableProvider('mymemory', 'MyMemory quota');

            return null;
        }

        return trim($translated) !== '' ? $translated : null;
    }

    private function translateWithGoogleGtx(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['gtx'])) {
            return null;
        }

        $this->throttle();
        try {
            $response = $this->httpClient()->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl' => $source,
                'tl' => $target,
                'dt' => 't',
                'ie' => 'UTF-8',
                'oe' => 'UTF-8',
                'q' => $text,
            ]);
        } catch (\Throwable $e) {
            $this->disableProvider('gtx', 'GTX timeout: '.$e->getMessage());

            return null;
        }

        if (! $response->ok()) {
            $this->disableProvider('gtx', 'GTX HTTP '.$response->status());

            return null;
        }

        return $this->parseGtxPayload($response->body());
    }

    private function parseGtxPayload(mixed $payload): ?string
    {
        if (is_string($payload)) {
            $payload = json_decode(trim($payload), true);
        }

        if (! is_array($payload) || ! isset($payload[0]) || ! is_array($payload[0])) {
            return null;
        }

        $translated = '';
        foreach ($payload[0] as $chunk) {
            if (is_array($chunk) && isset($chunk[0]) && is_string($chunk[0])) {
                $translated .= $chunk[0];
            }
        }

        return trim($translated) !== '' ? $translated : null;
    }
}
