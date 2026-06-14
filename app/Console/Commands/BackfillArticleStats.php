<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleStat;
use Illuminate\Console\Command;

class BackfillArticleStats extends Command
{
    protected $signature = 'articles:backfill-stats';

    protected $description = 'Initialise les statistiques articles à partir des view_count existants';

    public function handle(): int
    {
        $count = 0;

        Article::query()->chunkById(100, function ($articles) use (&$count) {
            foreach ($articles as $article) {
                ArticleStat::query()->firstOrCreate(
                    ['article_id' => $article->id],
                    [
                        'views_total' => $article->view_count,
                        'first_view_at' => $article->published_at,
                    ]
                );
                $count++;
            }
        });

        $this->info("Statistiques initialisées pour {$count} article(s).");

        return self::SUCCESS;
    }
}
