<?php

namespace App\Http\Middleware;

use App\Models\Article;
use App\Services\ArticleStatsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackArticleView
{
    public function __construct(private ArticleStatsService $stats) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET')) {
            return $response;
        }

        $slug = $request->route('slug');
        if (! $slug) {
            return $response;
        }

        $article = Article::query()
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->first(['id', 'slug', 'view_count', 'published_at']);

        if ($article) {
            $this->stats->recordPageView($article, $request);
        }

        return $response;
    }
}
