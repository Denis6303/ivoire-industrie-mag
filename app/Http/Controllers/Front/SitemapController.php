<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap unique regroupant toutes les URLs du site (fr + en).
     * Un seul fichier = moins de points de défaillance pour Google.
     */
    public function index(): Response
    {
        $appUrl  = rtrim(config('app.url'), '/');
        // Forcer HTTPS quel que soit APP_URL
        $appUrl  = preg_replace('/^http:\/\//', 'https://', $appUrl);
        $locales = config('ivoireindustriemag.supported_locales', ['fr', 'en']);

        // Pages statiques par locale
        $staticPages = [];
        foreach ($locales as $locale) {
            $staticPages[] = ['url' => "$appUrl/$locale/",           'priority' => '1.0', 'changefreq' => 'daily'];
            $staticPages[] = ['url' => "$appUrl/$locale/articles",   'priority' => '0.9', 'changefreq' => 'daily'];
            $staticPages[] = ['url' => "$appUrl/$locale/emplois",    'priority' => '0.7', 'changefreq' => 'daily'];
            $staticPages[] = ['url' => "$appUrl/$locale/entreprises",'priority' => '0.6', 'changefreq' => 'weekly'];
            $staticPages[] = ['url' => "$appUrl/$locale/a-propos",   'priority' => '0.5', 'changefreq' => 'monthly'];
            $staticPages[] = ['url' => "$appUrl/$locale/contact",    'priority' => '0.4', 'changefreq' => 'monthly'];
            $staticPages[] = ['url' => "$appUrl/$locale/equipe",     'priority' => '0.4', 'changefreq' => 'monthly'];
        }

        // Catégories
        $categories = Category::orderBy('name')->get();
        foreach ($locales as $locale) {
            foreach ($categories as $cat) {
                $staticPages[] = [
                    'url'        => "$appUrl/$locale/categories/{$cat->slug}",
                    'priority'   => '0.7',
                    'changefreq' => 'daily',
                    'updated'    => $cat->updated_at,
                ];
            }
        }

        // Articles publiés
        $articles = Article::select(['id', 'slug', 'cover_image', 'title', 'updated_at', 'published_at'])
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->get();

        $xml = view('seo.sitemap', compact('staticPages', 'articles', 'locales', 'appUrl'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('X-Robots-Tag', 'noindex'); // Le sitemap lui-même n'a pas besoin d'être indexé
    }
}
