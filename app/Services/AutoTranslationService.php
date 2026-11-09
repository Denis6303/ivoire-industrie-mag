<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    private const CHUNK_SIZE = 1500;

    public ?string $lastError = null;

    private static int $lastRequestMs = 0;

    /**
     * Traduit un texte. Retourne null si le fournisseur a échoué.
     */
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

        $cacheKey = 'auto_translation:v3:'.md5($source.'|'.$target.'|'.$text);
        $cached = Cache::get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        try {
            $translated = $this->looksLikeHtml($text)
                ? $this->translateHtml($text, $source, $target)
                : $this->translateChunks($text, $source, $target);
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

    private function looksLikeHtml(string $text): bool
    {
        return (bool) preg_match('/<\/?(p|div|h[1-6]|li|ul|ol|br|strong|em|a|span|blockquote)\b/i', $text);
    }

    /**
     * Traduit bloc par bloc pour ne pas envoyer tout le HTML d'un article d'un coup.
     */
    private function translateHtml(string $html, string $source, string $target): ?string
    {
        $parts = preg_split('/(?=<p\b|<h[1-6]\b|<li\b|<div\b|<blockquote\b|<ul\b|<ol\b)/i', $html) ?: [$html];
        $out = '';
        $anySuccess = false;

        foreach ($parts as $part) {
            if (trim($part) === '') {
                $out .= $part;
                continue;
            }

            if (! preg_match('/[A-Za-zÀ-ÿ]{3,}/u', strip_tags($part))) {
                $out .= $part;
                continue;
            }

            $translated = $this->translateChunks($part, $source, $target);
            if ($translated === null) {
                $out .= $part;
                continue;
            }
            $anySuccess = true;
            $out .= $translated;
        }

        return $anySuccess ? $out : null;
    }

    private function translateChunks(string $text, string $source, string $target): ?string
    {
        $chunks = $this->splitChunks($text);
        $out = '';

        foreach ($chunks as $chunk) {
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
        $provider = (string) config('translation.provider', 'google_free');

        if ($provider === 'libretranslate' && ! empty(config('translation.api_key'))) {
            $translated = $this->translateWithLibreTranslate($text, $source, $target);
            if ($translated !== null) {
                return $translated;
            }
        }

        $translated = $this->translateWithGoogleFree($text, $source, $target);
        if ($translated !== null) {
            return $translated;
        }

        return $this->translateWithMyMemory($text, $source, $target);
    }

    private function splitChunks(string $text): array
    {
        if (mb_strlen($text) <= self::CHUNK_SIZE) {
            return [$text];
        }

        $parts = preg_split('/(?<=<\/p>|<\/h[1-6]>|<\/li>|<\/div>|\. |\n\n)/u', $text) ?: [$text];
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
            ->connectTimeout(10)
            ->timeout((int) config('translation.timeout_seconds', 20))
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => '*/*',
            ]);
    }

    private function throttle(): void
    {
        $delay = max(0, (int) config('translation.request_delay_ms', 400));
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

    private function translateWithLibreTranslate(string $text, string $source, string $target): ?string
    {
        $endpoint = (string) config('translation.endpoint');
        if ($endpoint === '') {
            return null;
        }

        $this->throttle();
        $isHtml = strip_tags($text) !== $text;

        $response = $this->httpClient()
            ->acceptJson()
            ->asJson()
            ->post($endpoint, [
                'q' => $text,
                'source' => $source,
                'target' => $target,
                'format' => $isHtml ? 'html' : 'text',
                'api_key' => config('translation.api_key'),
            ]);

        if (! $response->ok()) {
            $this->lastError = 'LibreTranslate HTTP '.$response->status();

            return null;
        }

        $translated = (string) ($response->json('translatedText') ?? '');

        return trim($translated) !== '' ? $translated : null;
    }

    private function translateWithGoogleFree(string $text, string $source, string $target): ?string
    {
        $params = [
            'client' => 'gtx',
            'sl' => $source,
            'tl' => $target,
            'dt' => 't',
            'ie' => 'UTF-8',
            'oe' => 'UTF-8',
            'q' => $text,
        ];
        $url = 'https://translate.googleapis.com/translate_a/single';
        $attempts = max(1, (int) config('translation.retries', 4));

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $this->throttle();
            $usePost = mb_strlen($text) > 1200;
            $response = $usePost
                ? $this->httpClient()->asForm()->post($url, $params)
                : $this->httpClient()->get($url, $params);

            $status = $response->status();
            if (in_array($status, [429, 503, 502, 500], true)) {
                $this->lastError = 'Google HTTP '.$status;
                sleep(min(30, 2 ** $attempt));
                continue;
            }

            if (! $response->ok()) {
                $this->throttle();
                $response = $usePost
                    ? $this->httpClient()->get($url, $params)
                    : $this->httpClient()->asForm()->post($url, $params);
                $status = $response->status();
            }

            if ($response->ok()) {
                $parsed = $this->parseGooglePayload($response->body());
                if ($parsed !== null) {
                    return $parsed;
                }
                $this->lastError = 'réponse Google illisible';
            } else {
                $this->lastError = 'Google HTTP '.$status;
                if (in_array($status, [429, 503, 502, 500], true)) {
                    sleep(min(30, 2 ** $attempt));
                    continue;
                }
            }

            if ($attempt < $attempts) {
                sleep($attempt);
            }
        }

        Log::warning('Google translate failed', ['error' => $this->lastError]);

        return null;
    }

    private function parseGooglePayload(mixed $payload): ?string
    {
        if (is_string($payload)) {
            $payload = trim($payload);
            $decoded = json_decode($payload, true);
            if (! is_array($decoded)) {
                return null;
            }
            $payload = $decoded;
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

    private function translateWithMyMemory(string $text, string $source, string $target): ?string
    {
        if (mb_strlen($text) > 450) {
            return null;
        }

        $this->throttle();
        $response = $this->httpClient()->get('https://api.mymemory.translated.net/get', [
            'q' => $text,
            'langpair' => $source.'|'.$target,
        ]);

        if (! $response->ok()) {
            $this->lastError = 'MyMemory HTTP '.$response->status();

            return null;
        }

        $translated = (string) ($response->json('responseData.translatedText') ?? '');
        if (stripos($translated, 'MYMEMORY WARNING') !== false) {
            $this->lastError = 'MyMemory quota';

            return null;
        }

        return trim($translated) !== '' ? $translated : null;
    }
}
