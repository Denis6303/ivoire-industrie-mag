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

        $draftArticles  = Article::where('status', 'draft')->count();
        $totalArticles  = Article::count();
        $totalViews     = (int) Article::sum('view_count');
        $avgViews       = max(0, (int) round(Article::where('status', 'published')->avg('view_count') ?? 0));
        $totalComments  = Comment::count();

        $stats = [
            'published_articles'      => Article::where('status', 'published')->count(),
            'total_views'             => $totalViews,
            'newsletter_subscribers'  => NewsletterSubscription::whereIn('status', ['pending', 'active'])->count(),
            'total_comments'          => $totalComments,
            'published_this_month'    => $publishedThisMonth,
            'draft_articles'          => $draftArticles,
            'avg_views_per_article'   => $avgViews,
            'total_articles'          => $totalArticles,
        ];

        $stats['published_rate'] = $stats['total_articles'] > 0
            ? round(($stats['published_articles'] / $stats['total_articles']) * 100, 1)
            : 0;
        $stats['draft_rate'] = $stats['total_articles'] > 0
            ? round(($stats['draft_articles'] / $stats['total_articles']) * 100, 1)
            : 0;

        // Graphique : articles publiés par jour sur les 7 derniers jours
        // (Les vues journalières nécessiteraient une table dédiée ; on affiche uniquement les publications)
        $labels          = [];
        $publishedSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $day            = Carbon::today()->subDays($i);
            $labels[]       = $day->translatedFormat('d M');
            $publishedSeries[] = Article::where('status', 'published')
                ->whereDate('published_at', $day)
                ->count();
        }

        $chart = [
            'labels'    => $labels,
            'published' => $publishedSeries,
        ];

        return view('admin.dashboard', compact('stats', 'chart'));
    }
}
