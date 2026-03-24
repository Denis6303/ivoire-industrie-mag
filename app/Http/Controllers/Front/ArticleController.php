<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'category', 'tags', 'sectors'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('front.articles.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = Article::with(['author', 'category', 'tags', 'sectors', 'comments.replies'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('front.articles.show', compact('article', 'related'));
    }
}
