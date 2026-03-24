<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Company;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::with(['category', 'author'])->published()->featured()->latest('published_at')->first();
        $latest = Article::with(['category', 'author'])->published()->latest('published_at')->take(6)->get();
        $breaking = Article::published()->breaking()->latest('published_at')->take(8)->get();
        $companies = Company::where('is_active', true)->latest()->take(5)->get();

        return view('front.home', compact('featured', 'latest', 'breaking', 'companies'));
    }
}
