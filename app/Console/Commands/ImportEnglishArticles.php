<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportEnglishArticles extends Command
{
    protected $signature = 'articles:import-en
                            {path=storage/app/i18n-missing.json : Fichier JSON traduit}';

    protected $description = 'Importe les titres/chapeaux anglais depuis un JSON';

    public function handle(): int
    {
        $path = base_path(trim((string) $this->argument('path'), '/\\'));
        if (! is_file($path)) {
            $this->error('Fichier introuvable : '.$path);

            return self::FAILURE;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            $this->error('JSON invalide.');

            return self::FAILURE;
        }

        $ok = 0;
        foreach ($rows as $row) {
            $id = (int) ($row['id'] ?? 0);
            $titleEn = trim((string) ($row['title_en'] ?? ''));
            if ($id < 1 || $titleEn === '') {
                continue;
            }

            $article = Article::query()->find($id);
            if (! $article) {
                $this->warn("Article #{$id} introuvable.");
                continue;
            }

            $article->title_en = $titleEn;
            if (! empty($row['excerpt_en'])) {
                $article->excerpt_en = $row['excerpt_en'];
            }
            if (empty($article->slug_en)) {
                $article->slug_en = Str::slug($titleEn).'-'.Str::lower(Str::random(6));
            }
            $article->saveQuietly();
            $ok++;
        }

        $this->info("{$ok} article(s) mis à jour.");

        return $ok === 0 ? self::FAILURE : self::SUCCESS;
    }
}
