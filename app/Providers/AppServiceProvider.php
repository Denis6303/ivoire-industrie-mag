<?php

namespace App\Providers;

use App\Models\Article;
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
                $all = Category::query()->orderBy('name')->get()->keyBy('slug');

                $industrySlugs = collect([
                    'featured', 'breve', 'agro-industrie', 'agriculture', 'petrole', 'gaz', 'mines', 'sante',
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
                ]);

                $primaryCategories = $primarySlugs
                    ->map(fn ($slug) => $all->get($slug))
                    ->filter()
                    ->values();

                $navData = [
                    'industryCategories' => $industryCategories,
                    'primaryCategories' => $primaryCategories,
                ];

                request()->attributes->set('navData', $navData);
            }

            $view->with('navIndustryCategories', $navData['industryCategories'] ?? collect());
            $view->with('navPrimaryCategories', $navData['primaryCategories'] ?? collect());

            $breakingNews = request()->attributes->get('breakingNews');
            if ($breakingNews === null) {
                $breakingNews = Article::query()
                    ->published()
                    ->breaking()
                    ->latest('published_at')
                    ->limit(8)
                    ->get();
                if ($breakingNews->isEmpty()) {
                    $breakingNews = Article::query()
                        ->published()
                        ->latest('published_at')
                        ->limit(8)
                        ->get();
                }
                request()->attributes->set('breakingNews', $breakingNews);
            }
            $view->with('breakingNews', $breakingNews);
        });
    }
}
