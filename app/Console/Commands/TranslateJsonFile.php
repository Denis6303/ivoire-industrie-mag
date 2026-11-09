<?php

namespace App\Console\Commands;

use App\Services\AutoTranslationService;
use Illuminate\Console\Command;

class TranslateJsonFile extends Command
{
    protected $signature = 'articles:translate-json
                            {path=storage/app/i18n-missing.json : Fichier JSON exporté}';

    protected $description = 'Traduit un JSON d\'articles (à lancer en local, où les APIs fonctionnent)';

    public function handle(AutoTranslationService $translator): int
    {
        $path = base_path(trim((string) $this->argument('path'), '/\\'));
        if (! is_file($path)) {
            $this->error('Fichier introuvable : '.$path);

            return self::FAILURE;
        }

        if (! $translator->probe()) {
            $this->error('Aucune API de traduction joignable ici ('.$translator->lastError.').');

            return self::FAILURE;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            $this->error('JSON invalide.');

            return self::FAILURE;
        }

        $ok = 0;
        $fail = 0;

        foreach ($rows as $i => $row) {
            $title = (string) ($row['title'] ?? '');
            if ($title === '') {
                continue;
            }

            if (empty($row['title_en'])) {
                $translated = $translator->translate($title, 'fr', 'en');
                if (is_string($translated) && $translated !== '') {
                    $rows[$i]['title_en'] = $translated;
                    $ok++;
                    $this->line('OK #'.($row['id'] ?? '?').' '.$translated);
                } else {
                    $fail++;
                    $this->error('Échec #'.($row['id'] ?? '?').' '.($translator->lastError ?: ''));
                    if ($translator->isUnavailable()) {
                        break;
                    }
                    continue;
                }
            }

            $excerpt = (string) ($row['excerpt'] ?? '');
            if ($excerpt !== '' && empty($row['excerpt_en'])) {
                $excerptEn = $translator->translate($excerpt, 'fr', 'en');
                if (is_string($excerptEn) && $excerptEn !== '') {
                    $rows[$i]['excerpt_en'] = $excerptEn;
                }
            }
        }

        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->info("Terminé : {$ok} titre(s) traduit(s), {$fail} échec(s). Fichier : {$path}");

        return $ok === 0 ? self::FAILURE : self::SUCCESS;
    }
}
