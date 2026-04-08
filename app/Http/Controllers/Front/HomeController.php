<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Company;
use App\Models\IndustrialProject;
use App\Models\IndustrySector;
use App\Models\Tag;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::with(['category', 'author'])->published()->featured()->latest('published_at')->first();
        $latest = Article::with(['category', 'author'])->published()->latest('published_at')->take(6)->get();
        $companies = Company::where('is_active', true)->latest()->take(5)->get();
        $featuredCompanies = Company::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $projects = IndustrialProject::query()
            ->latest()
            ->take(6)
            ->get();

        $sectors = IndustrySector::query()
            ->where('is_active', true)
            ->withCount(['articles', 'companies', 'projects'])
            ->orderBy('order')
            ->orderBy('name')
            ->take(12)
            ->get();

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

        $topCategoriesForSidebar = Category::query()
            ->whereHas('articles', fn ($q) => $q->published())
            ->withCount(['articles as published_count' => fn ($q) => $q->published()])
            ->orderByDesc('published_count')
            ->take(5)
            ->get();

        $latestArticleByCategoryId = collect();
        if ($topCategoriesForSidebar->isNotEmpty()) {
            $latestArticleByCategoryId = Article::query()
                ->published()
                ->whereIn('category_id', $topCategoriesForSidebar->pluck('id'))
                ->with('category')
                ->orderByDesc('published_at')
                ->get()
                ->unique('category_id')
                ->keyBy('category_id');
        }

        $topCategoryPosts = $topCategoriesForSidebar
            ->map(function (Category $cat) use ($latestArticleByCategoryId) {
                $article = $latestArticleByCategoryId->get($cat->id);
                if (! $article) {
                    return null;
                }

                return ['category' => $cat, 'article' => $article];
            })
            ->filter()
            ->values();

        $sidebarTags = Tag::query()
            ->withCount(['articles' => fn ($q) => $q->published()])
            ->having('articles_count', '>', 0)
            ->orderByDesc('articles_count')
            ->limit(24)
            ->get();

        // Homepage organizer (12 sections)
        $homeSectionSlugs = [
            'industrie-story',
            'investissement',
            'zones-industrielles',
            'usines',
            'innovation',
            'international',
            'districts',
            'agenda',
            'made-in-ivory-coast',
            '2im-tv',
            'hommes-et-femmes-industriels-ivoiriens',
        ];

        $homeSections = Category::query()
            ->whereIn('slug', $homeSectionSlugs)
            ->get()
            ->sortBy(fn (Category $c) => array_search($c->slug, $homeSectionSlugs, true))
            ->values()
            ->map(function (Category $category) {
                $posts = Article::query()
                    ->with(['category', 'author'])
                    ->published()
                    ->where('category_id', $category->id)
                    ->latest('published_at')
                    ->take(4)
                    ->get();

                return [
                    'category' => $category,
                    'posts' => $posts,
                ];
            })
            ->filter(fn ($row) => $row['posts']->isNotEmpty())
            ->values();

        return view('front.home', compact(
            'featured',
            'latest',
            'companies',
            'featuredCompanies',
            'projects',
            'sectors',
            'sidebarLatest',
            'sidebarTrending',
            'sidebarPopular',
            'topCategoryPosts',
            'sidebarTags',
            'homeSections',
        ));
    }
}
