<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleTranslationService
{
    private const FIELDS = ['title', 'excerpt', 'content', 'meta_title', 'meta_description'];

    public function __construct(private AutoTranslationService $translator)
    {
    }

    /**
     * Remplit les champs anglais manquants (ou les régénère si le français a changé).
     */
    public function syncEnglish(Article $article, bool $force = false, ?array $fields = null): bool
    {
        $dirty = false;
        $fields = $fields ?: self::FIELDS;

        foreach ($fields as $field) {
            $enField = $field.'_en';
            $source = $article->{$field} ?? null;
            if (! is_string($source) || trim($source) === '') {
                continue;
            }

            $hasEn = is_string($article->{$enField} ?? null) && trim((string) $article->{$enField}) !== '';
            $frenchChanged = $article->wasChanged($field) && ! $article->wasChanged($enField);
            $shouldTranslate = $force || ! $hasEn || $frenchChanged;

            if (! $shouldTranslate) {
                continue;
            }

            $translated = $this->translator->translate($source, 'fr', 'en');
            if (! is_string($translated) || trim($translated) === '') {
                continue;
            }

            $article->{$enField} = $translated;
            $dirty = true;
        }

        if ($dirty && filled($article->title_en) && (empty($article->slug_en) || $article->wasChanged('title') || $force)) {
            $article->slug_en = Str::slug($article->title_en).'-'.Str::lower(Str::random(6));
        }

        if ($dirty) {
            $article->saveQuietly();
        }

        return $dirty;
    }
}
