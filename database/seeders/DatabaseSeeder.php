<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Données minimales pour une livraison propre du site.
     * - UserSeeder        : super admin uniquement
     * - CategorySeeder    : toutes les catégories et sous-catégories
     * - SiteSettingSeeder : paramètres du site (email, téléphone, réseaux…)
     *
     * Les seeders de démonstration (articles, tags, entreprises, projets,
     * newsletters, commentaires) sont volontairement exclus.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
