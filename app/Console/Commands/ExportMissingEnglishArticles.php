<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ExportMissingEnglishArticles extends Command
{
    protected $signature = 'articles:export-missing-en
                            {--path=storage/app/i18n-missing.json : Fichier JSON de sortie}';

    protected $description = 'Exporte les articles sans titre anglais (à traduire hors du serveur de prod)';

    public function handle(): int
    {
        $path = base_path(trim((string) $this->option('path'), '/\\'));
        $rows = [];

        Article::query()
            ->where(function ($q) {
                $q->whereNull('title_en')->orWhere('title_en', '');
            })
            ->orderBy('id')
            ->each(function (Article $article) use (&$rows) {
                $rows[] = [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => $article->excerpt,
                    'title_en' => null,
                    'excerpt_en' => null,
                ];
            });

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->info(count($rows).' article(s) exporté(s) vers '.$path);

        return self::SUCCESS;
    }
}
