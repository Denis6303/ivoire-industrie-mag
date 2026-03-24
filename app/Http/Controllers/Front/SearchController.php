<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $articles = null;

        if ($q !== '') {
            $articles = Article::with(['category', 'author'])
                ->published()
                ->whereFullText(['title', 'excerpt', 'content'], $q)
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('front.search.index', compact('q', 'articles'));
    }
}
