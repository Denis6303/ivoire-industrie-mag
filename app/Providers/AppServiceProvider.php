<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Paginator::useBootstrapFive();

        View::composer('front.*', function ($view) {
            $navData = request()->attributes->get('navData');
            if ($navData === null) {
                $all = Category::query()
                    ->whereHas('articles', fn ($q) => $q->published())
                    ->withCount(['articles as published_articles_count' => fn ($q) => $q->published()])
                    ->orderBy('name')
                    ->get()
                    ->keyBy('slug');

                $industrySlugs = collect([
                    'agro-industrie', 'agriculture', 'petrole', 'gaz', 'mines', 'sante',
                    'pharmaceutique', 'electronique', 'emballage-et-conditionnement', 'textile', 'bois',
                    'metallurgie', 'siderurgie', 'plasturgie', 'acteurs', 'aeronautique', 'automobile',
                    'btp', 'chimie', 'aquaculture', 'peche', 'boissons', 'electromecanique', 'energie',
                    'equipement', 'mecanique', 'materiaux', 'environnement', 'informatique',
                    'transport-et-logistique', 'evenementiel', 'sport',
                ]);

                $industryCategories = $industrySlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->values();

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
                    '2im-tv',
                    'magazine',
                    'emploi',
                ]);

                $innovationChildSlugs = collect([
                    'ingenierie',
                    'recherche-et-dev',
                    'technologie',
                    'ia',
                ]);

                $primaryCategories = $primarySlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->values();

                $hiddenCategories = $hiddenSlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->values();

                $innovationChildren = $innovationChildSlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->values();

                $navData = [
                    'industryCategories' => $industryCategories,
                    'primaryCategories' => $primaryCategories,
                    'hiddenCategories' => $hiddenCategories,
                    'innovationChildren' => $innovationChildren,
                ];

                request()->attributes->set('navData', $navData);
            }

            $view->with('navIndustryCategories', $navData['industryCategories'] ?? collect());
            $view->with('navPrimaryCategories', $navData['primaryCategories'] ?? collect());
            $view->with('navHiddenCategories', $navData['hiddenCategories'] ?? collect());
            $view->with('navInnovationChildren', $navData['innovationChildren'] ?? collect());

            $adsData = request()->attributes->get('adsData');
            if ($adsData === null) {
                $adsData = [
                    'header' => null,
                    'sidebar' => null,
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
                    $adsData['in_article'] = $activeAds->firstWhere('position', 'in_article');
                    $adsData['footer'] = $activeAds->firstWhere('position', 'footer');
                }

                request()->attributes->set('adsData', $adsData);
            }

            $view->with('adHeader', $adsData['header'] ?? null);
            $view->with('adSidebar', $adsData['sidebar'] ?? null);
            $view->with('adInArticle', $adsData['in_article'] ?? null);
            $view->with('adFooter', $adsData['footer'] ?? null);
        });
    }
}
