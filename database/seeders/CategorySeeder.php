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
            '#1B4332', '#b91c1c', '#334155', '#0ea5e9', '#be185d', '#15803d',
        ];

        // Rubriques principales (12 sections / menus)
        $primary = [
            'Industrie Story',
            'Investissement',
            'Zones industrielles',
            'Usines',
            'Innovation',
            'International',
            'Districts',
            'Agenda',
            'Made In Ivory Coast',
            '2IM TV',
            'Hommes et Femmes industriels ivoiriens',
        ];

        // Sous-catégories du menu Industrie (dropdown)
        $industry = [
            'Featured',
            'Brève',
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
            'Événementiel',
            'Sport',
        ];

        $categories = collect()
            ->merge($primary)
            ->merge($industry)
            ->values()
            ->unique()
            ->map(function (string $name, int $i) use ($palette) {
                return [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'color' => $palette[$i % count($palette)],
                ];
            })
            ->all();

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
