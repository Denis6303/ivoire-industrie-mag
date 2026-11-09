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

    private static int $consecutiveFailures = 0;

    /** @var array<string, bool> */
    private static array $disabledProviders = [];

    public function isUnavailable(): bool
    {
        return self::$consecutiveFailures >= 3;
    }

    public function probe(): bool
    {
        $result = $this->translate('Bonjour le monde', 'fr', 'en');

        return is_string($result) && mb_strtolower($result) !== 'bonjour le monde' && trim($result) !== '';
    }

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

        if ($this->isUnavailable()) {
            $this->lastError = 'APIs de traduction injoignables depuis ce serveur';

            return null;
        }

        $cacheKey = 'auto_translation:v5:'.md5($source.'|'.$target.'|'.$text);
        $cached = Cache::get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            self::$consecutiveFailures = 0;

            return $cached;
        }

        try {
            $translated = $this->translateChunks($text, $source, $target);
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            Log::warning('Auto translation failed', ['message' => $e->getMessage()]);
            self::$consecutiveFailures++;

            return null;
        }

        if ($translated === null || trim($translated) === '') {
            $this->lastError ??= 'traduction vide';
            self::$consecutiveFailures++;

            return null;
        }

        self::$consecutiveFailures = 0;
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
        $translated = $this->translateWithMyMemory($text, $source, $target);
        if ($translated !== null) {
            return $translated;
        }

        $translated = $this->translateWithGoogleChrome($text, $source, $target);
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

    /**
     * @param  array<string, mixed>  $params
     */
    private function httpGet(string $url, array $params = []): ?string
    {
        $this->throttle();
        $fullUrl = $params === [] ? $url : $url.'?'.http_build_query($params);

        try {
            $response = Http::withOptions([
                'verify' => (bool) config('translation.verify_ssl', false),
            ])
                ->connectTimeout(4)
                ->timeout((int) config('translation.timeout_seconds', 6))
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => '*/*',
                ])
                ->get($fullUrl);

            if ($response->ok()) {
                return $response->body();
            }
            $this->lastError = 'HTTP '.$response->status();
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
        }

        $body = $this->streamGet($fullUrl);
        if ($body !== null) {
            $this->lastError = null;
        }

        return $body;
    }

    private function streamGet(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => (int) config('translation.timeout_seconds', 6),
                'header' => "User-Agent: Mozilla/5.0\r\nAccept: */*\r\n",
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if (! is_string($body) || $body === '') {
            return null;
        }

        return $body;
    }

    private function translateWithMyMemory(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['mymemory']) || mb_strlen($text) > 450) {
            return null;
        }

        $query = [
            'q' => $text,
            'langpair' => $source.'|'.$target,
        ];
        $email = config('mail.from.address');
        if (is_string($email) && $email !== '') {
            $query['de'] = $email;
        }

        $body = $this->httpGet('https://api.mymemory.translated.net/get', $query);
        if ($body === null) {
            return null;
        }

        $payload = json_decode($body, true);
        $translated = (string) data_get($payload, 'responseData.translatedText', '');
        if (stripos($translated, 'MYMEMORY WARNING') !== false) {
            self::$disabledProviders['mymemory'] = true;
            $this->lastError = 'MyMemory quota';

            return null;
        }

        return trim($translated) !== '' ? $translated : null;
    }

    private function translateWithGoogleChrome(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['chrome'])) {
            return null;
        }

        $body = $this->httpGet('https://clients5.google.com/translate_a/t', [
            'client' => 'dict-chrome-ex',
            'sl' => $source,
            'tl' => $target,
            'q' => $text,
        ]);
        if ($body === null) {
            if (str_contains((string) $this->lastError, 'HTTP 429') || str_contains((string) $this->lastError, 'HTTP 403')) {
                self::$disabledProviders['chrome'] = true;
            }

            return null;
        }

        $payload = json_decode(trim($body), true);
        if (is_array($payload) && isset($payload[0]) && is_string($payload[0])) {
            $translated = trim(implode('', array_filter($payload, 'is_string')));

            return $translated !== '' ? $translated : null;
        }

        $this->lastError = 'réponse Chrome illisible';

        return null;
    }

    private function translateWithGoogleGtx(string $text, string $source, string $target): ?string
    {
        if (! empty(self::$disabledProviders['gtx'])) {
            return null;
        }

        $body = $this->httpGet('https://translate.googleapis.com/translate_a/single', [
            'client' => 'gtx',
            'sl' => $source,
            'tl' => $target,
            'dt' => 't',
            'q' => $text,
        ]);
        if ($body === null) {
            self::$disabledProviders['gtx'] = true;

            return null;
        }

        $payload = json_decode(trim($body), true);
        if (! is_array($payload) || ! isset($payload[0]) || ! is_array($payload[0])) {
            self::$disabledProviders['gtx'] = true;
            $this->lastError = 'GTX illisible';

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
