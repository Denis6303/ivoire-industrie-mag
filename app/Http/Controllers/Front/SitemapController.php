<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap index pointing to sub-sitemaps (articles + static pages).
     */
    public function index(): Response
    {
        $locales = config('ivoireindustriemag.supported_locales', ['fr', 'en']);

        $xml = view('seo.sitemap-index', compact('locales'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Static pages sitemap.
     */
    public function staticPages(string $locale): Response
    {
        $appUrl = rtrim(config('app.url'), '/');

        $pages = [
            ['url' => "$appUrl/$locale/",           'priority' => '1.0', 'changefreq' => 'daily',   'updated' => now()],
            ['url' => "$appUrl/$locale/articles",    'priority' => '0.9', 'changefreq' => 'daily',   'updated' => now()],
            ['url' => "$appUrl/$locale/recherche",   'priority' => '0.5', 'changefreq' => 'monthly', 'updated' => now()],
            ['url' => "$appUrl/$locale/emplois",     'priority' => '0.7', 'changefreq' => 'daily',   'updated' => now()],
            ['url' => "$appUrl/$locale/entreprises", 'priority' => '0.6', 'changefreq' => 'weekly',  'updated' => now()],
            ['url' => "$appUrl/$locale/a-propos",    'priority' => '0.5', 'changefreq' => 'monthly', 'updated' => now()],
            ['url' => "$appUrl/$locale/contact",     'priority' => '0.4', 'changefreq' => 'monthly', 'updated' => now()],
            ['url' => "$appUrl/$locale/equipe",      'priority' => '0.4', 'changefreq' => 'monthly', 'updated' => now()],
        ];

        // Ajoute toutes les catégories
        $categories = Category::orderBy('name')->get();
        foreach ($categories as $cat) {
            $pages[] = [
                'url'        => "$appUrl/$locale/categories/{$cat->slug}",
                'priority'   => '0.7',
                'changefreq' => 'daily',
                'updated'    => $cat->updated_at ?? now(),
            ];
        }

        $xml = view('seo.sitemap-static', compact('pages'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * Articles sitemap paginated (max 500 per file to stay below 50 MB limit).
     */
    public function articles(string $locale, int $page = 1): Response
    {
        $perPage = 500;
        $appUrl  = rtrim(config('app.url'), '/');

        $articles = Article::select(['id', 'slug', 'cover_image', 'title', 'updated_at', 'published_at'])
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->forPage($page, $perPage)
            ->get();

        $xml = view('seo.sitemap-articles', compact('articles', 'locale', 'appUrl'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
