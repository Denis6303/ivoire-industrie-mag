<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchService
{
    public function searchArticles(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return Article::with(['category', 'author'])
            ->published()
            ->whereFullText(['title', 'excerpt', 'content'], $query)
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
