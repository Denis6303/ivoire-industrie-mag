<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    private const CHUNK_SIZE = 4200;

    /**
     * Traduit un texte. Retourne null si le fournisseur a échoué (ne jamais
     * renvoyer le texte source dans ce cas, pour éviter de cacher un échec).
     */
    public function translate(string $text, string $source = 'fr', string $target = 'en'): ?string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text === '' ? null : $text;
        }

        $cacheKey = 'auto_translation:v2:'.md5($source.'|'.$target.'|'.$text);
        $cached = Cache::get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        try {
            $translated = $this->translateChunks($text, $source, $target);
        } catch (\Throwable $e) {
            Log::warning('Auto translation failed', ['message' => $e->getMessage()]);

            return null;
        }

        if ($translated === null || trim($translated) === '') {
            return null;
        }

        Cache::put($cacheKey, $translated, now()->addDays(30));

        return $translated;
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

        $parts = preg_split('/(?<=<\/p>|<\/h[1-6]>|<\/li>|<\/div>|\n\n)/u', $text) ?: [$text];
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
            ->timeout((int) config('translation.timeout_seconds', 8))
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/json,text/plain,*/*',
            ]);
    }

    private function translateWithLibreTranslate(string $text, string $source, string $target): ?string
    {
        $endpoint = (string) config('translation.endpoint');
        if ($endpoint === '') {
            return null;
        }

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
            'q' => $text,
        ];

        $response = $this->httpClient()->asForm()->post(
            'https://translate.googleapis.com/translate_a/single',
            $params
        );

        if (! $response->ok()) {
            $response = $this->httpClient()->get(
                'https://translate.googleapis.com/translate_a/single',
                $params
            );
        }

        if (! $response->ok()) {
            Log::warning('Google translate HTTP error', ['status' => $response->status()]);

            return null;
        }

        return $this->parseGooglePayload($response->json() ?? $response->body());
    }

    private function parseGooglePayload(mixed $payload): ?string
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
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

        $response = $this->httpClient()->get('https://api.mymemory.translated.net/get', [
            'q' => $text,
            'langpair' => $source.'|'.$target,
        ]);

        if (! $response->ok()) {
            return null;
        }

        $translated = (string) ($response->json('responseData.translatedText') ?? '');

        return trim($translated) !== '' ? $translated : null;
    }
}
