<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleTranslationService
{
    public const FIELDS = ['title', 'excerpt', 'content', 'meta_title', 'meta_description'];

    public function __construct(private AutoTranslationService $translator)
    {
    }

    /**
     * @param  list<string>|null  $fields
     * @return array{saved: bool, translated: list<string>, failed: array<string, string>, skipped: list<string>}
     */
    public function syncEnglish(Article $article, bool $force = false, ?array $fields = null): array
    {
        $dirty = false;
        $translated = [];
        $failed = [];
        $skipped = [];
        $fields = $fields ?: self::FIELDS;

        foreach ($fields as $field) {
            $enField = $field.'_en';
            $source = $article->{$field} ?? null;
            if (! is_string($source) || trim($source) === '') {
                $skipped[] = $field;
                continue;
            }

            $hasEn = is_string($article->{$enField} ?? null) && trim((string) $article->{$enField}) !== '';
            $frenchChanged = $article->wasChanged($field) && ! $article->wasChanged($enField);
            $shouldTranslate = $force || ! $hasEn || $frenchChanged;

            if (! $shouldTranslate) {
                $skipped[] = $field;
                continue;
            }

            $value = $this->translator->translate($source, 'fr', 'en');
            if (! is_string($value) || trim($value) === '') {
                $failed[$field] = $this->translator->lastError ?: 'échec fournisseur';
                continue;
            }

            $article->{$enField} = $value;
            $translated[] = $field;
            $dirty = true;
        }

        if ($dirty && filled($article->title_en) && (empty($article->slug_en) || $article->wasChanged('title') || $force)) {
            $article->slug_en = Str::slug($article->title_en).'-'.Str::lower(Str::random(6));
        }

        if ($dirty) {
            $article->saveQuietly();
        }

        return [
            'saved' => $dirty,
            'translated' => $translated,
            'failed' => $failed,
            'skipped' => $skipped,
        ];
    }
}
