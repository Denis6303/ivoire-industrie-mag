<?php

if (! function_exists('readingTime')) {
    function readingTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));

        return max(1, (int) ceil($words / 200));
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
            $segments[0] = $locale;

            return url(implode('/', $segments));
        }

        return url($locale);
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
