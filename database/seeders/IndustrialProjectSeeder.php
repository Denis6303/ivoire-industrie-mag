<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\IndustrialProject;
use App\Models\IndustrySector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndustrialProjectSeeder extends Seeder
{
    public function run(): void
    {
        $companyIds = Company::query()->pluck('id');
        $sectorIds = IndustrySector::query()->pluck('id');
        if ($sectorIds->isEmpty()) {
            return;
        }

        $projects = [
            [
                'name' => 'Extension de capacité — ligne de conditionnement cacao',
                'status' => 'in_progress',
                'location' => 'Zone industrielle de Yopougon',
                'investment' => 4_500_000_000,
                'jobs_created' => 120,
                'description' => "Projet d’extension visant à augmenter la capacité de conditionnement et à améliorer la qualité (tri optique, traçabilité, contrôles).\n\nObjectif: réduire les pertes, augmenter la cadence et renforcer la conformité export.",
            ],
            [
                'name' => 'Centrale solaire pour site industriel',
                'status' => 'planned',
                'location' => 'Bonoua',
                'investment' => 2_100_000_000,
                'jobs_created' => 35,
                'description' => "Déploiement d’une centrale solaire avec stockage pour couvrir une partie des besoins énergétiques.\n\nLe projet inclut un système de supervision et un plan de maintenance.",
            ],
            [
                'name' => 'Modernisation d’une unité de préfabrication béton',
                'status' => 'completed',
                'location' => 'Anyama',
                'investment' => 1_650_000_000,
                'jobs_created' => 50,
                'description' => "Renouvellement d’équipements, optimisation des flux et formation des opérateurs.\n\nRésultat attendu: délais réduits et meilleure régularité de production.",
            ],
            [
                'name' => 'Plateforme data & maintenance prédictive',
                'status' => 'in_progress',
                'location' => 'Abidjan',
                'investment' => 380_000_000,
                'jobs_created' => 18,
                'description' => "Mise en place d’une plateforme IoT et de tableaux de bord de performance.\n\nObjectif: réduire les pannes, fiabiliser les KPI et améliorer la qualité.",
            ],
            [
                'name' => 'Unité de froid & logistique agro',
                'status' => 'planned',
                'location' => 'Bouaké',
                'investment' => 920_000_000,
                'jobs_created' => 42,
                'description' => "Construction d’un entrepôt frigorifique et d’une flotte de transport sous température contrôlée.\n\nBut: limiter les pertes post-récolte et sécuriser la chaîne d’approvisionnement.",
            ],
            [
                'name' => 'Recyclage et valorisation de déchets industriels',
                'status' => 'planned',
                'location' => 'San-Pédro',
                'investment' => 1_250_000_000,
                'jobs_created' => 60,
                'description' => "Création d’une filière de tri et de valorisation (plastiques, cartons, sous-produits).\n\nLe projet s’inscrit dans une démarche d’économie circulaire.",
            ],
        ];

        foreach ($projects as $p) {
            $slug = Str::slug($p['name']);

            IndustrialProject::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $p['name'],
                    'slug' => $slug,
                    'description' => $p['description'] ?? null,
                    'investment' => $p['investment'] ?? null,
                    'jobs_created' => $p['jobs_created'] ?? null,
                    'location' => $p['location'] ?? null,
                    'start_date' => now()->subMonths(random_int(1, 18))->toDateString(),
                    'end_date' => null,
                    'status' => $p['status'] ?? 'planned',
                    'industry_sector_id' => $sectorIds->random(),
                    'company_id' => $companyIds->isNotEmpty() ? $companyIds->random() : null,
                ]
            );
        }

        // Ajouter quelques projets supplémentaires pour densifier la page “Projets”.
        for ($i = 1; $i <= 10; $i++) {
            $name = "Projet industriel démonstration #{$i}";
            $slug = Str::slug($name);
            $locations = ['Abidjan', 'Bouaké', 'San-Pédro', 'Yamoussoukro', 'Korhogo'];
            $statuses = ['planned', 'in_progress', 'completed'];

            IndustrialProject::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "Description de démonstration pour le projet #{$i}.\n\nObjectifs, impacts, calendrier, risques et opportunités (texte factice).",
                    'investment' => random_int(120_000_000, 8_500_000_000),
                    'jobs_created' => random_int(10, 280),
                    'location' => $locations[array_rand($locations)],
                    'start_date' => now()->subMonths(random_int(1, 24))->toDateString(),
                    'end_date' => null,
                    'status' => $statuses[array_rand($statuses)],
                    'industry_sector_id' => $sectorIds->random(),
                    'company_id' => $companyIds->isNotEmpty() ? $companyIds->random() : null,
                ]
            );
        }
    }
}

