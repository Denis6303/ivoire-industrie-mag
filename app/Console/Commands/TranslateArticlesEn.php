<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticleTranslationService;
use Illuminate\Console\Command;

class TranslateArticlesEn extends Command
{
    protected $signature = 'articles:translate-en
                            {--force : Régénère même si une version anglaise existe déjà}
                            {--id= : Limiter à un article}';

    protected $description = 'Génère les champs anglais manquants (titre, châpeau, contenu) pour les articles';

    public function handle(ArticleTranslationService $translator): int
    {
        $query = Article::query();
        if ($this->option('id')) {
            $query->where('id', (int) $this->option('id'));
        } elseif (! $this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('title_en')
                    ->orWhere('title_en', '')
                    ->orWhereNull('excerpt_en')
                    ->orWhere('excerpt_en', '')
                    ->orWhereNull('content_en')
                    ->orWhere('content_en', '');
            });
        }

        $total = (clone $query)->count();
        $this->info("Articles à traiter : {$total}");

        $ok = 0;
        $fail = 0;

        $query->orderBy('id')->chunkById(20, function ($articles) use ($translator, &$ok, &$fail) {
            foreach ($articles as $article) {
                $done = $translator->syncEnglish($article, (bool) $this->option('force'));
                if ($done || (filled($article->title_en) && filled($article->content_en))) {
                    $ok++;
                    $this->line("OK #{$article->id} {$article->title}");
                } else {
                    $fail++;
                    $this->warn("Échec #{$article->id} {$article->title}");
                }
            }
        });

        $this->info("Terminé : {$ok} traduit(s), {$fail} échec(s).");

        return $fail > 0 && $ok === 0 ? self::FAILURE : self::SUCCESS;
    }
}
