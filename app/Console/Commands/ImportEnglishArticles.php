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
        $path = $this->resolvePath((string) $this->argument('path'));
        if (! is_file($path)) {
            $this->error('Fichier introuvable : '.$path);
            $this->line('Placez le JSON traduit dans storage/app/i18n-missing.json (pas à la racine de public_html).');

            return self::FAILURE;
        }

        $raw = (string) file_get_contents($path);
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw) ?? $raw;
        $rows = json_decode($raw, true);
        if (! is_array($rows)) {
            $this->error('JSON invalide dans '.$path);

            return self::FAILURE;
        }

        if (isset($rows['id']) || isset($rows['title_en'])) {
            $rows = [$rows];
        }

        $total = count($rows);
        $withEn = 0;
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            if (trim((string) ($row['title_en'] ?? '')) !== '') {
                $withEn++;
            }
        }

        $this->comment('Fichier : '.$path.' ('.strlen($raw).' octets)');
        $this->comment("Lignes : {$total} | avec title_en : {$withEn}");

        if ($withEn === 0) {
            $this->error('Ce fichier n\'est pas traduit (title_en vide partout).');
            $this->line('Vous avez probablement réimporté l\'export d\'origine.');
            $this->line('Écrasez storage/app/i18n-missing.json par le fichier traduit depuis le PC local, puis relancez.');
            $sample = is_array($rows[0] ?? null) ? implode(', ', array_keys($rows[0])) : '(vide)';
            $this->line('Clés de la 1re ligne : '.$sample);

            return self::FAILURE;
        }

        $ok = 0;
        $missing = 0;
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $id = (int) ($row['id'] ?? 0);
            $titleEn = trim((string) ($row['title_en'] ?? ''));
            if ($id < 1 || $titleEn === '') {
                continue;
            }

            $article = Article::query()->find($id);
            if (! $article) {
                $missing++;
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

        $this->info("{$ok} article(s) mis à jour.".($missing ? " {$missing} ID introuvable(s)." : ''));

        return $ok === 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolvePath(string $path): string
    {
        $path = trim($path, " \t\n\r\0\x0B\"'");
        if ($path !== '' && is_file($path)) {
            return $path;
        }

        $candidates = [
            base_path($path),
            storage_path('app/'.basename($path)),
            storage_path('app/i18n-missing.json'),
            base_path('i18n-missing.json'),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return base_path($path);
    }
}
