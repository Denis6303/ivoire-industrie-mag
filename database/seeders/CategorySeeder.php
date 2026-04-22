<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $palette = [
            '#ff7800', '#1e40af', '#065f46', '#7c3aed', '#0f766e', '#92400e',
            '#1b4332', '#b91c1c', '#334155', '#0ea5e9', '#be185d', '#15803d',
        ];

        $tree = [
            'Industrie Story' => [],
            'Industrie' => [
                'Agro-industrie',
                'Agriculture',
                'Pétrole',
                'Gaz',
                'Mines',
                'Santé',
                'Pharmaceutique',
                'Electronique',
                'Emballage et Conditionnement',
                'Textile',
                'Bois',
                'Métallurgie',
                'Sidérurgie',
                'Plasturgie',
                'Acteurs',
                'Aéronautique',
                'Automobile',
                'BTP',
                'Chimie',
                'Aquaculture',
                'Pêche',
                'Boissons',
                'Electromécanique',
                'Energie',
                'Equipement',
                'Mécanique',
                'Matériaux',
                'Environnement',
                'Informatique',
                'Transport et Logistique',
                'Produits de consommation',
                'Hydraulique',
                'Entrepreneuriat',
                'Événementiel',
                'Sport',
                'Économie',
                'Aérien',
                'Aviation',
            ],
            'Investissement' => [],
            'Zones industrielles' => [],
            'Usines' => [],
            'Innovation' => [
                'Ingénierie',
                'R&D',
                'Technologie',
                'IA',
            ],
            'International' => [
                'Afrique',
                'Monde',
            ],
            'Districts' => [],
            'Agenda' => [],
            'Made In Ivory Coast' => [],
            'Études' => [],
            '2IM TV' => [],
            'Hommes et Femmes industriels ivoiriens' => [],
            'Dossier' => [],
            'Magazine' => [],
        ];

        $colorIndex = 0;
        $parentOrder = 1;

        foreach ($tree as $parentName => $children) {
            $parentSlug = Str::slug($parentName);
            $parent = Category::updateOrCreate(
                ['slug' => $parentSlug],
                [
                    'name' => $parentName,
                    'slug' => $parentSlug,
                    'description' => null,
                    'color' => $palette[$colorIndex % count($palette)],
                    'icon' => null,
                    'parent_id' => null,
                    'order' => $parentOrder,
                ]
            );

            $colorIndex++;
            $parentOrder++;

            foreach (array_values($children) as $childOrder => $childName) {
                $childSlug = Str::slug($childName);

                Category::updateOrCreate(
                    ['slug' => $childSlug],
                    [
                        'name' => $childName,
                        'slug' => $childSlug,
                        'description' => null,
                        'color' => $palette[$colorIndex % count($palette)],
                        'icon' => null,
                        'parent_id' => $parent->id,
                        'order' => $childOrder + 1,
                    ]
                );

                $colorIndex++;
            }
        }
    }
}
