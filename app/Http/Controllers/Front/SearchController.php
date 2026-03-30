<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $articles = null;

        if ($q !== '' && mb_strlen($q) >= 2) {
            $articles = app(SearchService::class)->searchArticles($q, 12);
        }

        return view('front.search.index', compact('q', 'articles'));
    }
}
