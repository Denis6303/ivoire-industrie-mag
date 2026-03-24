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
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('articles', ArticleController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('secteurs', SectorController::class);
Route::resource('entreprises', CompanyController::class);
Route::resource('projets', ProjectController::class);
Route::resource('commentaires', CommentController::class);
Route::resource('medias', MediaController::class);
Route::resource('newsletter', NewsletterController::class);
Route::resource('publicites', AdvertisementController::class);
Route::get('parametres', [SettingController::class, 'index'])->name('settings');
Route::post('parametres', [SettingController::class, 'update'])->name('settings.update');

Route::patch('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
Route::patch('commentaires/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
