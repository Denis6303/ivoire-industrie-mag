<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\NewsletterSubscription;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'published_articles' => Article::where('status', 'published')->count(),
            'views_today' => Article::whereDate('updated_at', today())->sum('view_count'),
            'newsletter_subscribers' => NewsletterSubscription::where('status', 'active')->count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
        ];

        $recentArticles = Article::with('category')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentArticles'));
    }
}
