<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ExportMissingEnglishArticles extends Command
{
    protected $signature = 'articles:export-missing-en
                            {--content : Exporter le corps (content) des articles encore en français}
                            {--path= : Fichier JSON de sortie}';

    protected $description = 'Exporte les articles à traduire hors du serveur de prod';

    public function handle(): int
    {
        $contentOnly = (bool) $this->option('content');
        $pathOption = trim((string) $this->option('path'));
        $relative = $pathOption !== ''
            ? $pathOption
            : ($contentOnly ? 'storage/app/i18n-content.json' : 'storage/app/i18n-missing.json');
        $path = base_path(trim($relative, '/\\'));
        $rows = [];

        $query = Article::query()->orderBy('id');
        if ($contentOnly) {
            $query->where(function ($q) {
                $q->whereNull('content_en')->orWhere('content_en', '');
            })->whereNotNull('content')->where('content', '!=', '');
        } else {
            $query->where(function ($q) {
                $q->whereNull('title_en')->orWhere('title_en', '');
            });
        }

        $query->each(function (Article $article) use (&$rows, $contentOnly) {
            if ($contentOnly) {
                $rows[] = [
                    'id' => $article->id,
                    'content' => $article->content,
                    'content_en' => null,
                ];

                return;
            }

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
