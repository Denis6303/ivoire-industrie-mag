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
            $navCategories = request()->attributes->get('navCategories');
            if ($navCategories === null) {
                $navCategories = Category::query()->orderBy('name')->limit(12)->get();
                request()->attributes->set('navCategories', $navCategories);
            }
            $view->with('navCategories', $navCategories);

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
