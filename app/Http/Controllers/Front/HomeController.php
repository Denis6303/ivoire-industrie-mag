<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Company;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::with(['category', 'author'])->published()->featured()->latest('published_at')->first();
        $latest = Article::with(['category', 'author'])->published()->where('type', '!=', 'breve')->latest('published_at')->take(2)->get();
        $breves = Article::with(['category', 'author'])
            ->published()
            ->where('type', 'breve')
            ->latest('published_at')
            ->take(4)
            ->get();
        $brevesTotal = Article::published()->where('type', 'breve')->count();
        $companies = Company::where('is_active', true)->latest()->take(5)->get();
        $featuredCompanies = Company::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $sidebarPopular = Article::with('category')->published()
            ->orderByDesc('view_count')
            ->latest('published_at')
            ->take(6)
            ->get();

        // Homepage organizer: ordre principal + ordre hamburger (si articles)
        $homeSectionSlugs = [
            'industrie-story',
            'zones-industrielles',
            'investissement',
            'usines',
            'international',
            'agenda',
            'innovation',
            'hommes-et-femmes-industriels-ivoiriens',
            'dossier',
            'districts',
            'made-in-ivory-coast',
            '2im-tv',
            'magazine',
            'emploi',
        ];

        $homeSections = Category::query()
            ->whereIn('slug', $homeSectionSlugs)
            ->get()
            ->sortBy(fn (Category $c) => array_search($c->slug, $homeSectionSlugs, true))
            ->values()
            ->map(function (Category $category) {
                $totalPosts = Article::query()
                    ->published()
                    ->where('category_id', $category->id)
                    ->count();

                $posts = Article::query()
                    ->with(['category', 'author'])
                    ->published()
                    ->where('category_id', $category->id)
                    ->latest('published_at')
                    ->take(2)
                    ->get();

                return [
                    'category' => $category,
                    'posts' => $posts,
                    'total_posts' => $totalPosts,
                ];
            })
            ->filter(fn ($row) => $row['posts']->isNotEmpty())
            ->values();

        return view('front.home', compact(
            'featured',
            'latest',
            'breves',
            'brevesTotal',
            'companies',
            'featuredCompanies',
            'sidebarPopular',
            'homeSections',
        ));
    }
}
