<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Front\AdvertisementController;
use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\CommentController;
use App\Http\Controllers\Front\CompanyController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\ProjectController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Front\SectorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/'.config('app.locale', 'fr'));
});

Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/ads/{ad}/click', [AdvertisementController::class, 'trackClick'])->name('ads.click');

$supportedLocales = config('ivoireindustriemag.supported_locales', ['fr', 'en']);
$localePattern = implode('|', $supportedLocales);

Route::prefix('{locale}')
    ->where(['locale' => $localePattern])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show')->middleware('track.article.view');

        Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('/secteurs', [SectorController::class, 'index'])->name('sectors.index');
        Route::get('/secteurs/{slug}', [SectorController::class, 'show'])->name('sectors.show');

        Route::get('/entreprises', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/entreprises/{slug}', [CompanyController::class, 'show'])->name('companies.show');

        Route::get('/projets', [ProjectController::class, 'index'])->name('projects.index');

        Route::get('/recherche', [SearchController::class, 'index'])->name('search');

        Route::view('/a-propos', 'front.about')->name('about');
        Route::view('/contact', 'front.contact')->name('contact');
        Route::view('/equipe', 'front.team')->name('team');

        Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

        Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

        Route::middleware('guest')->group(function () {
            Route::get('/connexion', [ClientAuthController::class, 'showLogin'])->name('login');
            Route::post('/connexion', [ClientAuthController::class, 'login'])->name('login.post');
            Route::get('/inscription', [ClientAuthController::class, 'showRegister'])->name('register');
            Route::post('/inscription', [ClientAuthController::class, 'register'])->name('register.post');
        });

        Route::post('/deconnexion', [ClientAuthController::class, 'logout'])->middleware('auth')->name('logout');
    });

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');
