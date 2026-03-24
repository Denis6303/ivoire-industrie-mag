<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Actualités', 'slug' => 'actualites', 'color' => '#1B4332'],
            ['name' => 'Analyses', 'slug' => 'analyses', 'color' => '#1e40af'],
            ['name' => 'Interviews', 'slug' => 'interviews', 'color' => '#7c3aed'],
            ['name' => 'Reportages', 'slug' => 'reportages', 'color' => '#065f46'],
            ['name' => 'Tribunes', 'slug' => 'tribunes', 'color' => '#92400e'],
            ['name' => 'Données & Chiffres', 'slug' => 'donnees-chiffres', 'color' => '#1e3a5f'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
