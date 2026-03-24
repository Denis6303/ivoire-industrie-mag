<?php

namespace Database\Seeders;

use App\Models\IndustrySector;
use Illuminate\Database\Seeder;

class IndustrySectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['name' => 'Agroalimentaire', 'slug' => 'agroalimentaire', 'color' => '#16a34a', 'icon' => 'wheat'],
            ['name' => 'Mines & Carrières', 'slug' => 'mines-carrieres', 'color' => '#78716c', 'icon' => 'pickaxe'],
            ['name' => 'Énergie & Pétrole', 'slug' => 'energie-petrole', 'color' => '#d97706', 'icon' => 'zap'],
            ['name' => 'BTP & Infrastructures', 'slug' => 'btp-infrastructures', 'color' => '#2563eb', 'icon' => 'building'],
            ['name' => 'Textile & Habillement', 'slug' => 'textile-habillement', 'color' => '#7c3aed', 'icon' => 'scissors'],
            ['name' => 'Chimie & Pharmacie', 'slug' => 'chimie-pharmacie', 'color' => '#0891b2', 'icon' => 'flask'],
            ['name' => 'TIC & Numérique', 'slug' => 'tic-numerique', 'color' => '#0f766e', 'icon' => 'cpu'],
            ['name' => 'Industrie Manufacturière', 'slug' => 'industrie-manufacturiere', 'color' => '#b45309', 'icon' => 'factory'],
        ];

        foreach ($sectors as $sector) {
            IndustrySector::updateOrCreate(['slug' => $sector['slug']], $sector);
        }
    }
}
