<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function publish(Article $article): Article
    {
        $article->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $article->refresh();
    }
}
