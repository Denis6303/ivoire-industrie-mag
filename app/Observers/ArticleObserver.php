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

        // Titre / châpeau seulement à la sauvegarde (le contenu HTML est trop long pour bloquer l'admin).
        app(ArticleTranslationService::class)->syncEnglish(
            $article,
            false,
            ['title', 'excerpt', 'meta_title', 'meta_description']
        );
    }
}
