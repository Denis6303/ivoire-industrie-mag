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

Route::get('/', [HomeController::class, 'index']);

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show')->middleware('track.article.view');

Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/secteurs', [SectorController::class, 'index'])->name('sectors.index');
Route::get('/secteurs/{slug}', [SectorController::class, 'show'])->name('sectors.show');

Route::get('/entreprises', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/entreprises/{slug}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/projets', [ProjectController::class, 'index'])->name('projects.index');

Route::get('/recherche', [SearchController::class, 'index'])->name('search');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/ads/{ad}/click', [AdvertisementController::class, 'trackClick'])->name('ads.click');

// Aliases pour les pages du template (liens statiques dans index.html)
Route::get('/index.html', [HomeController::class, 'index'])->name('index.html');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [ClientAuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [ClientAuthController::class, 'login'])->name('login.post');
    Route::get('/inscription', [ClientAuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [ClientAuthController::class, 'register'])->name('register.post');
    Route::get('/sign-in.html', [ClientAuthController::class, 'showLogin']);
    Route::get('/sign-up.html', [ClientAuthController::class, 'showRegister']);
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

Route::post('/deconnexion', [ClientAuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::get('/about.html', fn () => view('front.about'))->name('about');
Route::get('/contact.html', fn () => view('front.contact'))->name('contact');
Route::get('/team.html', fn () => view('front.team'));
Route::get('/404.html', fn () => response()->view('errors.404', [], 404));

// Compatibilité (anciens liens du template)
Route::get('/about-us.html', fn () => redirect('/about.html'));
Route::get('/contact-us.html', fn () => redirect('/contact.html'));

// Variantes du template (liens dans le menu du template)
Route::get('/index-02.html', [HomeController::class, 'index'])->name('index-02.html');
Route::get('/index-03.html', [HomeController::class, 'index'])->name('index-03.html');
Route::get('/index-04.html', [HomeController::class, 'index'])->name('index-04.html');
Route::get('/index-05.html', [HomeController::class, 'index'])->name('index-05.html');
Route::get('/index-06.html', [HomeController::class, 'index'])->name('index-06.html');

// Templates de listings / catégories (liens du template)
Route::get('/categories-style-01.html', fn () => view('front.categories.show'))->name('categories-style-01.html');
Route::get('/categories-style-02.html', fn () => view('front.sectors.index'))->name('categories-style-02.html');
Route::get('/categories-style-03.html', fn () => view('front.companies.index'))->name('categories-style-03.html');
Route::get('/categories-style-04.html', fn () => view('front.projects.index'))->name('categories-style-04.html');
Route::get('/categories-style-05.html', fn () => view('front.search.index'))->name('categories-style-05.html');
Route::get('/categories-style-06.html', fn () => view('front.articles.index'))->name('categories-style-06.html');

// Détails article (liens du template)
Route::get('/blog-single-01.html', fn () => view('front.articles.show'))->name('blog-single-01.html');
Route::get('/blog-single-02.html', fn () => view('front.articles.show'))->name('blog-single-02.html');
Route::get('/blog-single-03.html', fn () => view('front.articles.show'))->name('blog-single-03.html');
Route::get('/blog-single-04.html', fn () => view('front.articles.show'))->name('blog-single-04.html');
Route::get('/blog-single-05.html', fn () => view('front.articles.show'))->name('blog-single-05.html');

Route::get('/author.html', fn () => view('front.companies.show'))->name('author.html');
