<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\IndustrySector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $sectors = IndustrySector::query()->pluck('id', 'slug');
        if ($sectors->isEmpty()) {
            return;
        }

        $companies = [
            [
                'name' => 'Ivoire AgroTech',
                'city' => 'Abidjan',
                'region' => 'Lagunes',
                'industry_sector_slug' => 'agroalimentaire',
                'website' => 'https://example.com',
                'description' => "Transformation de matières premières locales (cacao, noix de cajou, manioc) avec un focus sur la traçabilité, la qualité et l’export.\n\nL’entreprise développe des partenariats avec des coopératives et modernise ses lignes de conditionnement pour répondre aux standards internationaux.",
                'logo' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=200&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Lagunes Ciment & Béton',
                'city' => 'Abidjan',
                'region' => 'Lagunes',
                'industry_sector_slug' => 'btp-infrastructures',
                'website' => 'https://example.com',
                'description' => "Production de ciment, béton prêt à l’emploi et solutions préfabriquées pour les chantiers urbains et industriels.\n\nLa société investit dans des unités plus économes en énergie et dans la valorisation de sous-produits pour réduire l’empreinte carbone.",
                'logo' => 'https://images.unsplash.com/photo-1523958203904-cdcb402031fd?auto=format&fit=crop&w=200&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Koumassi Steel Works',
                'city' => 'Abidjan',
                'region' => 'Lagunes',
                'industry_sector_slug' => 'industrie-manufacturiere',
                'website' => 'https://example.com',
                'description' => "Laminage et transformation de produits métalliques (treillis, barres, profils) pour le BTP et l’industrie.\n\nL’entreprise mise sur la maintenance prédictive et la formation interne pour stabiliser la qualité et réduire les arrêts.",
                'logo' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?auto=format&fit=crop&w=200&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Savane Mines Services',
                'city' => 'Korhogo',
                'region' => 'Poro',
                'industry_sector_slug' => 'mines-carrieres',
                'website' => 'https://example.com',
                'description' => "Services et sous-traitance pour l’exploitation minière: logistique, maintenance, pièces de rechange et sécurité.\n\nObjectif: améliorer la disponibilité des engins et la conformité HSE sur site.",
                'logo' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=200&q=80',
                'is_featured' => false,
            ],
            [
                'name' => 'Abidjan Renewables',
                'city' => 'Abidjan',
                'region' => 'Lagunes',
                'industry_sector_slug' => 'energie-petrole',
                'website' => 'https://example.com',
                'description' => "Développement de projets solaires et hybrides pour sites industriels et zones isolées.\n\nLa société propose des contrats de performance énergétique et du monitoring à distance.",
                'logo' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=200&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'BioPharma CI',
                'city' => 'Yamoussoukro',
                'region' => 'Lacs',
                'industry_sector_slug' => 'chimie-pharmacie',
                'website' => 'https://example.com',
                'description' => "Conditionnement et distribution de produits pharmaceutiques, avec une chaîne qualité inspirée des bonnes pratiques (BPF).\n\nDéploiement d’un système de sérialisation et d’une traçabilité renforcée.",
                'logo' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=200&q=80',
                'is_featured' => false,
            ],
            [
                'name' => 'Digital Factory Lab',
                'city' => 'Abidjan',
                'region' => 'Lagunes',
                'industry_sector_slug' => 'tic-numerique',
                'website' => 'https://example.com',
                'description' => "Intégration et conseil: ERP, MES, IoT industriel, cybersécurité et data.\n\nLe lab accompagne la digitalisation des usines (tableaux de bord, maintenance prédictive, qualité).",
                'logo' => 'https://images.unsplash.com/photo-1556155092-490a1ba16284?auto=format&fit=crop&w=200&q=80',
                'is_featured' => true,
            ],
            [
                'name' => 'Textiles de Grand-Bassam',
                'city' => 'Grand-Bassam',
                'region' => 'Sud-Comoé',
                'industry_sector_slug' => 'textile-habillement',
                'website' => 'https://example.com',
                'description' => "Atelier et production textile: uniforms, vêtements professionnels et séries courtes.\n\nModernisation des métiers à tisser et développement d’une gamme “made in CI”.",
                'logo' => 'https://images.unsplash.com/photo-1520975958225-5bbdca4a21c1?auto=format&fit=crop&w=200&q=80',
                'is_featured' => false,
            ],
        ];

        // Compléter avec d’autres entreprises “génériques” pour garnir l’annuaire.
        $genericNames = [
            ['Sassandra Packaging', 'industrie-manufacturiere', 'Sassandra', 'Gbôklé'],
            ['Bouaké Logistics & Cold', 'agroalimentaire', 'Bouaké', 'Gbêkê'],
            ['San-Pédro Port Industries', 'btp-infrastructures', 'San-Pédro', 'Bas-Sassandra'],
            ['Comoe Energies', 'energie-petrole', 'Aboisso', 'Sud-Comoé'],
            ['Yopougon Chemical', 'chimie-pharmacie', 'Abidjan', 'Lagunes'],
            ['Man Data Services', 'tic-numerique', 'Man', 'Tonkpi'],
            ['Daloa Wood & Panels', 'industrie-manufacturiere', 'Daloa', 'Haut-Sassandra'],
            ['Bondoukou Agro Export', 'agroalimentaire', 'Bondoukou', 'Gontougo'],
            ['Odienné Carrières', 'mines-carrieres', 'Odienné', 'Kabadougou'],
            ['Ferkessédougou Textiles', 'textile-habillement', 'Ferkessédougou', 'Tchologo'],
            ['Abengourou Cacao Tech', 'agroalimentaire', 'Abengourou', 'Indénié-Djuablin'],
            ['Anyama Industrial Park', 'btp-infrastructures', 'Anyama', 'Lagunes'],
        ];

        foreach ($genericNames as [$name, $sectorSlug, $city, $region]) {
            $companies[] = [
                'name' => $name,
                'city' => $city,
                'region' => $region,
                'industry_sector_slug' => $sectorSlug,
                'website' => 'https://example.com',
                'description' => "Fiche de présentation (texte factice) pour {$name}.\n\nActivités, capacités, partenaires, enjeux d’investissement et projets à venir.",
                'logo' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=200&q=80',
                'is_featured' => false,
            ];
        }

        foreach ($companies as $c) {
            $slug = Str::slug($c['name']);
            $sectorId = $sectors[$c['industry_sector_slug']] ?? null;

            Company::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $c['name'],
                    'slug' => $slug,
                    'logo' => $c['logo'] ?? null,
                    'description' => $c['description'] ?? null,
                    'website' => $c['website'] ?? null,
                    'email' => null,
                    'phone' => null,
                    'city' => $c['city'] ?? null,
                    'region' => $c['region'] ?? null,
                    'address' => null,
                    'is_featured' => (bool) ($c['is_featured'] ?? false),
                    'is_active' => true,
                    'industry_sector_id' => $sectorId,
                ]
            );
        }
    }
}

