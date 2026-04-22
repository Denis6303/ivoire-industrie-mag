<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // En production, forcer toutes les URLs générées par Laravel en HTTPS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        Paginator::useBootstrapFive();

        View::composer('front.*', function ($view) {
            $navData = request()->attributes->get('navData');
            if ($navData === null) {
                $all = Category::query()
                    ->withCount(['articles as published_articles_count' => fn ($q) => $q->published()])
                    ->orderBy('order')
                    ->orderBy('name')
                    ->get()
                    ->keyBy('slug');

                $industryParent = $all->get('industrie');
                $industryCategories = collect();
                if ($industryParent) {
                    $industryCategories = Category::query()
                        ->where('parent_id', $industryParent->id)
                        ->whereHas('articles', fn ($q) => $q->published())
                        ->orderBy('order')
                        ->orderBy('name')
                        ->get();
                }

                $primarySlugs = collect([
                    'industrie-story',
                    'zones-industrielles',
                    'investissement',
                    'usines',
                    'international',
                    'agenda',
                ]);

                $hiddenSlugs = collect([
                    'innovation',
                    'hommes-et-femmes-industriels-ivoiriens',
                    'dossier',
                    'districts',
                    'made-in-ivory-coast',
                    'etudes',
                    '2im-tv',
                    'magazine',
                    'emploi',
                ]);

                $hasPublishedChild = static function (Category $cat): bool {
                    return Category::query()
                        ->where('parent_id', $cat->id)
                        ->whereHas('articles', fn ($q) => $q->published())
                        ->exists();
                };

                $primaryCategories = $primarySlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->filter(fn ($cat) => (int) ($cat->published_articles_count ?? 0) > 0 || $hasPublishedChild($cat))
                    ->values();

                $hiddenCategories = $hiddenSlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->filter(fn ($cat) => (int) ($cat->published_articles_count ?? 0) > 0 || $hasPublishedChild($cat))
                    ->values();

                $innovation = $all->get('innovation');
                $innovationChildren = collect();
                if ($innovation) {
                    $innovationChildren = Category::query()
                        ->where('parent_id', $innovation->id)
                        ->whereHas('articles', fn ($q) => $q->published())
                        ->orderBy('order')
                        ->orderBy('name')
                        ->get();
                }

                $navData = [
                    'industryParent' => $industryParent,
                    'industryCategories' => $industryCategories,
                    'primaryCategories' => $primaryCategories,
                    'hiddenCategories' => $hiddenCategories,
                    'innovationChildren' => $innovationChildren,
                ];

                request()->attributes->set('navData', $navData);
            }

            $view->with('navIndustryParent', $navData['industryParent'] ?? null);
            $view->with('navIndustryCategories', $navData['industryCategories'] ?? collect());
            $view->with('navPrimaryCategories', $navData['primaryCategories'] ?? collect());
            $view->with('navHiddenCategories', $navData['hiddenCategories'] ?? collect());
            $view->with('navInnovationChildren', $navData['innovationChildren'] ?? collect());

            $adsData = request()->attributes->get('adsData');
            if ($adsData === null) {
                $adsData = [
                    'header' => null,
                    'sidebar' => null,
                    'sidebar_secondary' => null,
                    'in_article' => null,
                    'footer' => null,
                ];

                if (Schema::hasTable('advertisements')) {
                    $activeAds = Advertisement::query()
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('start_date')
                                ->orWhere('start_date', '<=', now());
                        })
                        ->where(function ($q) {
                            $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', now());
                        })
                        ->orderByDesc('id')
                        ->get();

                    $adsData['header'] = $activeAds->firstWhere('position', 'header');
                    $adsData['sidebar'] = $activeAds->firstWhere('position', 'sidebar');
                    $adsData['sidebar_secondary'] = $activeAds->firstWhere('position', 'sidebar_secondary');
                    $adsData['in_article'] = $activeAds->firstWhere('position', 'in_article');
                    $adsData['footer'] = $activeAds->firstWhere('position', 'footer');
                }

                request()->attributes->set('adsData', $adsData);
            }

            $view->with('adHeader', $adsData['header'] ?? null);
            $view->with('adSidebar', $adsData['sidebar'] ?? null);
            $view->with('adSidebarSecondary', $adsData['sidebar_secondary'] ?? null);
            $view->with('adInArticle', $adsData['in_article'] ?? null);
            $view->with('adFooter', $adsData['footer'] ?? null);
        });
    }
}
