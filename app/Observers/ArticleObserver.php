<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\ArticleTranslationService;

class ArticleObserver
{
    public function saved(Article $article): void
    {
        if (
            ! $article->wasRecentlyCreated
            && ! $article->wasChanged(['title', 'excerpt', 'content', 'meta_title', 'meta_description'])
        ) {
            return;
        }

        $api = app(\App\Services\AutoTranslationService::class);
        if ($api->isUnavailable()) {
            return;
        }

        app(ArticleTranslationService::class)->syncEnglish(
            $article,
            false,
            ['title', 'excerpt', 'meta_title', 'meta_description']
        );
    }
}
