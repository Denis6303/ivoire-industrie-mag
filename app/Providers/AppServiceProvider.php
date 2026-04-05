<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
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

        $locale = config('ivoireindustriemag.default_locale', 'fr');
        app()->setLocale($locale);
        Carbon::setLocale($locale);

        Paginator::useBootstrapFive();

        View::composer('front.*', function ($view) {
            $navCategories = request()->attributes->get('navCategories');
            if ($navCategories === null) {
                $navCategories = Category::query()->orderBy('name')->limit(12)->get();
                request()->attributes->set('navCategories', $navCategories);
            }
            $view->with('navCategories', $navCategories);
        });
    }
}
