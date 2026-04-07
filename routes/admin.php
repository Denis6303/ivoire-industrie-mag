<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('role:super_admin,admin,editor')
    ->name('dashboard');

Route::middleware('role:super_admin,admin,editor')->group(function () {
    Route::resource('articles', ArticleController::class);
    Route::patch('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
    Route::patch('articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
});

Route::middleware('role:super_admin,admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::resource('secteurs', SectorController::class);
    Route::resource('entreprises', CompanyController::class);
    Route::resource('projets', ProjectController::class);
    Route::resource('commentaires', CommentController::class);
    Route::resource('medias', MediaController::class);
    Route::resource('newsletter', NewsletterController::class);
    Route::resource('publicites', AdvertisementController::class);
    Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
    Route::get('parametres', [SettingController::class, 'index'])->name('settings');
    Route::post('parametres', [SettingController::class, 'update'])->name('settings.update');
    Route::patch('commentaires/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
});
