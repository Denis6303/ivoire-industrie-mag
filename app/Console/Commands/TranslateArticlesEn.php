<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticleTranslationService;
use App\Services\AutoTranslationService;
use Illuminate\Console\Command;

class TranslateArticlesEn extends Command
{
    protected $signature = 'articles:translate-en
                            {--force : Régénère même si une version anglaise existe déjà}
                            {--id= : Limiter à un article}
                            {--incomplete : Inclure aussi les articles déjà OK en titre mais incomplets (châpeau/contenu)}
                            {--with-content : Traduire aussi le corps HTML (plus lent)}
                            {--skip-content : (déprécié) identique au comportement par défaut}
                            {--sleep=0.15 : Pause en secondes entre les articles}';

    protected $description = 'Traduit uniquement les articles en échec (sans titre anglais), sans retoucher ceux déjà OK';

    public function handle(ArticleTranslationService $translator, AutoTranslationService $api): int
    {
        $this->comment('Test de connexion aux APIs de traduction...');
        if (! $api->probe()) {
            $this->error('Impossible de traduire depuis ce serveur ('.$api->lastError.').');
            $this->line('Le serveur de production n\'a probablement pas accès à Google/MyMemory.');
            $this->newLine();
            $this->info('Procédure qui fonctionne :');
            $this->line('1) Ici en prod :  php artisan articles:export-missing-en');
            $this->line('2) En local     :  php artisan articles:translate-json');
            $this->line('3) Ici en prod :  php artisan articles:import-en');

            return self::FAILURE;
        }
        $this->info('API OK : « '.$api->translate('Bonjour le monde', 'fr', 'en').' »');

        $fields = ArticleTranslationService::FIELDS;
        if (! $this->option('with-content')) {
            $fields = array_values(array_filter($fields, fn ($field) => $field !== 'content'));
        }

        $query = Article::query();
        if ($this->option('id')) {
            $query->where('id', (int) $this->option('id'));
        } elseif ($this->option('force')) {
            $this->comment('Mode --force : tous les articles seront retraités.');
        } elseif ($this->option('incomplete')) {
            $query->where(function ($q) use ($fields) {
                foreach ($fields as $i => $field) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->{$method}(function ($inner) use ($field) {
                        $inner->whereNull($field.'_en')->orWhere($field.'_en', '');
                    });
                }
            });
            $this->comment('Mode --incomplete : articles avec au moins un champ anglais manquant.');
        } else {
            // Par défaut : uniquement les échecs (pas de titre anglais).
            $query->where(function ($q) {
                $q->whereNull('title_en')->orWhere('title_en', '');
            });
            $this->comment('Mode défaut : uniquement les échecs (title_en vide). Les succès sont ignorés.');
        }

        $total = (clone $query)->count();
        $this->info("Articles à traiter : {$total}");
        if ($total === 0) {
            $this->info('Rien à faire.');

            return self::SUCCESS;
        }

        $ok = 0;
        $partial = 0;
        $fail = 0;
        $sleep = max(0, (float) $this->option('sleep'));

        $query->orderBy('id')->chunkById(10, function ($articles) use ($translator, $api, $fields, $sleep, &$ok, &$partial, &$fail) {
            foreach ($articles as $article) {
                $result = $translator->syncEnglish($article, (bool) $this->option('force'), $fields);
                $hasTitle = filled($article->title_en);
                $hasContent = ! in_array('content', $fields, true) || filled($article->content_en) || ! filled($article->content);

                if ($hasTitle && $hasContent && $result['failed'] === []) {
                    $ok++;
                    $this->line("OK #{$article->id} {$article->title}");
                } elseif ($hasTitle) {
                    $partial++;
                    $reason = $this->formatFailures($result['failed']);
                    $this->warn("Partiel #{$article->id} {$article->title}".($reason ? " ({$reason})" : ''));
                } else {
                    $fail++;
                    $reason = $this->formatFailures($result['failed']) ?: 'titre non traduit';
                    $this->error("Échec #{$article->id} {$article->title} ({$reason})");
                    if ($api->isUnavailable() || $fail >= 3 && $ok === 0) {
                        $this->error('Arrêt : les APIs ne répondent pas depuis ce serveur.');
                        $this->line('Utilisez articles:export-missing-en → translate-json (en local) → import-en.');

                        return false;
                    }
                }

                if ($sleep > 0) {
                    usleep((int) round($sleep * 1_000_000));
                }
            }
        });

        $this->info("Terminé : {$ok} OK, {$partial} partiel(s), {$fail} échec(s).");
        if ($partial > 0 && $this->option('with-content')) {
            $this->comment('Certains contenus HTML ont échoué. Relancer sans --with-content puis avec, plus tard.');
        }

        return $fail > 0 && $ok === 0 && $partial === 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $failed
     */
    private function formatFailures(array $failed): string
    {
        if ($failed === []) {
            return '';
        }

        $parts = [];
        foreach ($failed as $field => $error) {
            $parts[] = $field.': '.$error;
        }

        return implode(', ', $parts);
    }
}
