<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'category', 'tags', 'sectors'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $sidebarCategories = Category::sidebarListWithPublishedCounts();

        return view('front.articles.index', compact('articles', 'sidebarCategories'));
    }

    public function show(string $locale, string $slug)
    {
        $articleQuery = Article::with([
            'author',
            'category',
            'tags',
            'sectors',
            'comments' => function ($q) {
                $q->where('is_approved', true)
                    ->whereNull('parent_id')
                    ->with([
                        'user',
                        'replies' => function ($r) {
                            $r->where('is_approved', true)->with('user');
                        },
                    ]);
            },
        ])
            ->published();

        if ($locale === 'en') {
            $articleQuery->where(function ($query) use ($slug) {
                $query->where('slug_en', $slug)
                    ->orWhere(function ($fallbackQuery) use ($slug) {
                        $fallbackQuery->whereNull('slug_en')->where('slug', $slug);
                    });
            });
        } else {
            $articleQuery->where('slug', $slug);
        }

        $article = $articleQuery->firstOrFail();

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $recentArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('front.articles.show', compact('article', 'related', 'recentArticles'));
    }
}
