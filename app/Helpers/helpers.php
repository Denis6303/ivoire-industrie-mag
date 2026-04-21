<?php

if (! function_exists('readingTime')) {
    /**
     * Temps de lecture estimé (≈ 200 mots/min), basé sur châpeau + corps en clair.
     * Comptage compatible UTF-8 (français).
     */
    function readingTime(string $content): int
    {
        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', trim($text));
        if ($text === '') {
            return 1;
        }
        $tokens = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $count = is_array($tokens) ? count($tokens) : 0;

        return max(1, (int) ceil($count / 200));
    }
}

if (! function_exists('formatNumber')) {
    function formatNumber(int|float $number): string
    {
        return number_format((float) $number, 0, ',', ' ');
    }
}

if (! function_exists('switch_locale_url')) {
    /**
     * URL courante avec un autre segment de langue (ex. /fr/articles → /en/articles).
     */
    function switch_locale_url(string $locale): string
    {
        $supported = config('ivoireindustriemag.supported_locales', ['fr', 'en']);
        if (! in_array($locale, $supported, true)) {
            return url('/'.config('app.locale'));
        }

        $path = trim(request()->path(), '/');
        $segments = $path === '' ? [] : explode('/', $path);

        if (count($segments) > 0 && in_array($segments[0], $supported, true)) {
            // Article detail: remap slug to locale-specific slug when available.
            if (($segments[1] ?? null) === 'articles' && ! empty($segments[2])) {
                $currentSlug = $segments[2];
                $article = \App\Models\Article::query()
                    ->where('slug', $currentSlug)
                    ->orWhere('slug_en', $currentSlug)
                    ->first();

                if ($article) {
                    $segments[2] = $locale === 'en'
                        ? ($article->slug_en ?: $article->slug)
                        : $article->slug;
                }
            }

            $segments[0] = $locale;

            return url(implode('/', $segments));
        }

        return url($locale);
    }
}

if (! function_exists('article_i18n')) {
    function article_i18n(\App\Models\Article $article, string $field): ?string
    {
        $locale = app()->getLocale();
        $englishField = $field.'_en';

        if (
            $locale === 'en'
            && isset($article->{$englishField})
            && is_string($article->{$englishField})
            && trim($article->{$englishField}) !== ''
        ) {
            return $article->{$englishField};
        }

        $value = $article->{$field} ?? null;
        if (! is_string($value)) {
            return null;
        }

        if ($locale === 'en' && in_array($field, ['title', 'excerpt', 'content', 'meta_title', 'meta_description'], true)) {
            return app(\App\Services\AutoTranslationService::class)->translate($value, 'fr', 'en');
        }

        return $value;
    }
}

if (! function_exists('article_route_slug')) {
    function article_route_slug(\App\Models\Article $article): string
    {
        if (app()->getLocale() === 'en' && ! empty($article->slug_en)) {
            return $article->slug_en;
        }

        return $article->slug;
    }
}

if (! function_exists('youtube_video_id_from_text')) {
    /**
     * Extrait le premier ID vidéo YouTube trouvé dans une chaîne (HTML ou texte).
     */
    function youtube_video_id_from_text(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return null;
        }

        $patterns = [
            '~youtube\.com/watch\?[^#]*\bv=([a-zA-Z0-9_-]{11})~',
            '~youtu\.be/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/embed/([a-zA-Z0-9_-]{11})~',
            '~youtube\.com/shorts/([a-zA-Z0-9_-]{11})~',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                return $m[1];
            }
        }

        return null;
    }
}

if (! function_exists('article_youtube_video_id')) {
    function article_youtube_video_id(\App\Models\Article $article): ?string
    {
        foreach ([$article->content, $article->excerpt] as $blob) {
            if (! is_string($blob) || $blob === '') {
                continue;
            }
            $id = youtube_video_id_from_text($blob);
            if ($id !== null) {
                return $id;
            }
        }

        return null;
    }
}

if (! function_exists('category_i18n')) {
    function category_i18n(?\App\Models\Category $category): string
    {
        if (! $category) {
            return '';
        }

        if (app()->getLocale() !== 'en') {
            return (string) $category->name;
        }

        $map = [
            'industrie-story' => 'nav.industry_story',
            'industrie' => 'nav.industry',
            'zones-industrielles' => 'nav.industrial_zones',
            'investissement' => 'nav.investment',
            'usines' => 'nav.factory',
            'international' => 'nav.international',
            'agenda' => 'nav.agenda',
            'innovation' => 'nav.innovation',
            'hommes-et-femmes-industriels-ivoiriens' => 'nav.industrial_leaders',
            'dossier' => 'nav.dossier',
            'districts' => 'nav.districts',
            'made-in-ivory-coast' => 'nav.made_in_ivory_coast',
            'etudes' => 'nav.studies',
            '2im-tv' => 'nav.tv',
            'magazine' => 'nav.magazine',
            'emploi' => 'nav.jobs',
            'breve' => 'front.briefs',
        ];

        $key = $map[$category->slug] ?? null;

        return $key ? __($key) : (string) $category->name;
    }
}

if (! function_exists('category_route')) {
    /**
     * URL catégorie avec contournement du cache 301 historique sur "industrie".
     */
    function category_route(string $slug): string
    {
        $params = ['slug' => $slug];
        if ($slug === 'industrie') {
            $params['v'] = '2';
        }

        return route('categories.show', $params);
    }
}

if (! function_exists('category_show_url')) {
    function category_show_url(?\App\Models\Category $category): string
    {
        return category_route((string) ($category?->slug ?? ''));
    }
}

if (! function_exists('article_body_html')) {
    /**
     * Corps d’article WYSIWYG : retire une enveloppe <!DOCTYPE>/<html>/<head>/<body>
     * pour éviter de casser le layout (feuilles de style ignorées).
     */
    function article_body_html(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $out = preg_replace('/\s*<!DOCTYPE[^>]*>/i', '', $html);
        $out = preg_replace('/<\s*html[^>]*>/i', '', $out);
        $out = preg_replace('/<\s*\/\s*html\s*>/i', '', $out);
        $out = preg_replace('/<\s*head[^>]*>.*?<\/\s*head\s*>/is', '', $out);
        $out = preg_replace('/<\s*body[^>]*>/i', '', $out);
        $out = preg_replace('/<\s*\/\s*body\s*>/i', '', $out);
        // Peut retargeter toutes les URLs relatives (y compris assets déjà chargés)
        $out = preg_replace('/<\s*base\b[^>]*>/i', '', $out);
        // Feuilles de style relatives (ex. href="css/…") résolues depuis /fr/articles/… → 404
        $out = preg_replace_callback('/<link\b[^>]*\brel\s*=\s*["\']stylesheet["\'][^>]*>/i', function (array $m) {
            $tag = $m[0];
            if (preg_match('/\bhref\s*=\s*["\'](https?:\/\/|\/|data:)/i', $tag)) {
                return $tag;
            }

            return '';
        }, $out);

        return trim($out);
    }
}

if (! function_exists('article_cover')) {
    /**
     * URL de couverture article (chaîne déjà absolue ou chemin stocké côté médias).
     */
    function article_cover(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        // URL absolue distante (CDN, autre domaine): on conserve.
        if (preg_match('/^https?:\/\//i', $url) === 1) {
            $parts = parse_url($url);
            $path = $parts['path'] ?? '';

            // Si l'image pointe vers /storage/... sur un ancien host local,
            // on recale vers le host courant.
            if (str_starts_with($path, '/storage/')) {
                return $path;
            }

            return $url;
        }

        // Chemins locaux relatifs/absolus vers storage.
        if (str_starts_with($url, '/storage/')) {
            return $url;
        }

        if (str_starts_with($url, 'storage/')) {
            return '/'.ltrim($url, '/');
        }

        // Chemin média brut (ex: media/abc.jpg) -> /storage/media/abc.jpg
        if (str_starts_with($url, 'media/')) {
            return '/storage/'.ltrim($url, '/');
        }

        return asset(ltrim($url, '/'));
    }
}

if (! function_exists('site_setting_fallback')) {
    function site_setting_fallback(string $key, ?string $default): string
    {
        if ($default !== null && $default !== '') {
            return $default;
        }
        $mail = config('mail.from.address');

        return match ($key) {
            'contact_email' => is_string($mail) ? $mail : '',
            'contact_phone' => '',
            'contact_address' => 'Abidjan, Côte d’Ivoire',
            'social_facebook' => (string) data_get(config('ivoireindustriemag.social'), 'facebook.url', '#'),
            'social_x' => (string) data_get(config('ivoireindustriemag.social'), 'twitter.url', '#'),
            'social_linkedin' => (string) data_get(config('ivoireindustriemag.social'), 'linkedin.url', '#'),
            'social_instagram' => (string) data_get(config('ivoireindustriemag.social'), 'instagram.url', '#'),
            'social_youtube' => (string) data_get(config('ivoireindustriemag.social'), 'youtube.url', '#'),
            default => '',
        };
    }
}

if (! function_exists('flush_site_settings_cache')) {
    function flush_site_settings_cache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('site_settings_flat');
    }
}

if (! function_exists('site_setting')) {
    /**
     * Valeur paramétrable en admin (table site_settings), avec repli sur config / .env.
     */
    function site_setting(string $key, ?string $default = null): string
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                return site_setting_fallback($key, $default);
            }
        } catch (\Throwable $e) {
            return site_setting_fallback($key, $default);
        }

        $flat = \Illuminate\Support\Facades\Cache::remember('site_settings_flat', 3600, function () {
            try {
                return \App\Models\SiteSetting::query()->pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                return [];
            }
        });

        $value = $flat[$key] ?? null;
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }

        return site_setting_fallback($key, $default);
    }
}
