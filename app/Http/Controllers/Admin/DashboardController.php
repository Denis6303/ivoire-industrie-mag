<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth();
        $publishedThisMonth = Article::where('status', 'published')
            ->whereBetween('published_at', [$monthStart, now()])
            ->count();

        $draftArticles = Article::where('status', 'draft')->count();
        $totalArticles = Article::count();
        $totalViews = (int) Article::sum('view_count');
        $avgViews = max(0, (int) round(Article::avg('view_count') ?? 0));
        $pendingRate = Comment::count() > 0
            ? round((Comment::where('is_approved', false)->count() / Comment::count()) * 100, 1)
            : 0;

        $stats = [
            'published_articles' => Article::where('status', 'published')->count(),
            'views_today' => Article::whereDate('updated_at', today())->sum('view_count'),
            'newsletter_subscribers' => NewsletterSubscription::where('status', 'active')->count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
            'published_this_month' => $publishedThisMonth,
            'draft_articles' => $draftArticles,
            'total_views' => $totalViews,
            'avg_views_per_article' => $avgViews,
            'pending_comments_rate' => $pendingRate,
            'total_articles' => $totalArticles,
        ];

        $stats['published_rate'] = $stats['total_articles'] > 0
            ? round(($stats['published_articles'] / $stats['total_articles']) * 100, 1)
            : 0;
        $stats['draft_rate'] = $stats['total_articles'] > 0
            ? round(($stats['draft_articles'] / $stats['total_articles']) * 100, 1)
            : 0;

        $labels = [];
        $publishedSeries = [];
        $viewsSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->translatedFormat('d M');
            $publishedSeries[] = Article::whereDate('published_at', $day)->count();
            $viewsSeries[] = (int) Article::whereDate('updated_at', $day)->sum('view_count');
        }

        $chart = [
            'labels' => $labels,
            'published' => $publishedSeries,
            'views' => $viewsSeries,
        ];

        return view('admin.dashboard', compact('stats', 'chart'));
    }
}
