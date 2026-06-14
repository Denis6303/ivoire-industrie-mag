<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ArticleStatsService;
use Illuminate\View\View;

class ArticleStatsController extends Controller
{
    public function __construct(private ArticleStatsService $stats) {}

    public function show(Article $article): View
    {
        $this->ensureEditorOwnsArticle($article);

        $article->load(['author', 'category', 'tags']);
        $article->loadCount(['comments as comments_total', 'comments as comments_approved' => fn ($q) => $q->where('is_approved', true)]);

        $report = $this->stats->buildReport($article);

        $chartDaily = $report['daily']->map(fn ($row) => [
            'label' => $row->date->format('d/m'),
            'views' => $row->views,
            'unique' => $row->unique_visitors,
        ])->values();

        return view('admin.articles.stats', compact('article', 'report', 'chartDaily'));
    }

    private function ensureEditorOwnsArticle(Article $article): void
    {
        if (auth()->user()?->role === 'editor' && $article->author_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez consulter que les statistiques de vos propres contenus.');
        }
    }
}
