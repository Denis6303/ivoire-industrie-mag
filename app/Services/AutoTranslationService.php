<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    public function translate(string $text, string $source = 'fr', string $target = 'en'): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        $cacheKey = 'auto_translation:'.md5($source.'|'.$target.'|'.$text);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $source, $target) {
            try {
                $provider = (string) config('translation.provider', 'google_free');

                if ($provider === 'libretranslate' && ! empty(config('translation.api_key'))) {
                    $translated = $this->translateWithLibreTranslate($text, $source, $target);
                    if ($translated !== null) {
                        return $translated;
                    }
                }

                $translated = $this->translateWithGoogleFree($text, $source, $target);

                return $translated ?? $text;
            } catch (\Throwable $e) {
                Log::warning('Auto translation failed', ['message' => $e->getMessage()]);

                return $text;
            }
        });
    }

    private function translateWithLibreTranslate(string $text, string $source, string $target): ?string
    {
        $endpoint = (string) config('translation.endpoint');
        if ($endpoint === '') {
            return null;
        }

        $isHtml = strip_tags($text) !== $text;

        $response = Http::withOptions([
            'verify' => (bool) config('translation.verify_ssl', false),
        ])
            ->timeout((int) config('translation.timeout_seconds', 8))
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
        $response = Http::withOptions([
            'verify' => (bool) config('translation.verify_ssl', false),
        ])
            ->timeout((int) config('translation.timeout_seconds', 8))
            ->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl' => $source,
                'tl' => $target,
                'dt' => 't',
                'q' => $text,
            ]);

        if (! $response->ok()) {
            return null;
        }

        $payload = $response->json();
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
