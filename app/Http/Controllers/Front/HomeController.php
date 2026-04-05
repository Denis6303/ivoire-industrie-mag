<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Company;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::with(['category', 'author'])->published()->featured()->latest('published_at')->first();
        $latest = Article::with(['category', 'author'])->published()->latest('published_at')->take(6)->get();
        $companies = Company::where('is_active', true)->latest()->take(5)->get();

        $sidebarLatest = Article::with('category')->published()->latest('published_at')->take(3)->get();

        $sidebarTrending = Article::with('category')->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderByDesc('view_count')
            ->take(3)
            ->get();
        if ($sidebarTrending->isEmpty()) {
            $sidebarTrending = Article::with('category')->published()
                ->orderByDesc('view_count')
                ->take(3)
                ->get();
        }

        $sidebarPopular = Article::with('category')->published()
            ->orderByDesc('view_count')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('front.home', compact(
            'featured',
            'latest',
            'companies',
            'sidebarLatest',
            'sidebarTrending',
            'sidebarPopular',
        ));
    }
}
