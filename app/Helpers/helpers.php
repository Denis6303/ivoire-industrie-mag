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

if (! function_exists('article_cover')) {
    /**
     * URL de couverture article (chaîne déjà absolue ou chemin stocké côté médias).
     */
    function article_cover(?string $url): ?string
    {
        return $url ?: null;
    }
}
