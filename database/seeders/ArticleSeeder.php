<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\IndustrySector;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::where('email', 'admin@ivoireindustriemag.ci')->first();
        $categories = Category::query()->get()->keyBy('slug');
        $tagIds = Tag::query()->pluck('id');
        $sectorIds = IndustrySector::query()->pluck('id');

        if (! $author || $categories->isEmpty()) {
            return;
        }

        $covers = [
            ['url' => 'https://images.unsplash.com/photo-1581091870627-3d5a8c1e0c4f?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Chaîne de production industrielle'],
            ['url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Logistique et conteneurs'],
            ['url' => 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Énergie solaire'],
            ['url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Innovation et énergie'],
            ['url' => 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Électronique et technologie'],
            ['url' => 'https://images.unsplash.com/photo-1555664424-778a1e5e1b48?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Construction et infrastructures'],
            ['url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Équipe et formation'],
            ['url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Données et tableaux de bord'],
            ['url' => 'https://images.unsplash.com/photo-1466629437334-b4f6603563c5?auto=format&fit=crop&w=1400&q=80', 'alt' => 'Industrie agroalimentaire'],
        ];

        $filler = implode('', [
            '<p>Ce texte est une description factice mais structurée, conçue pour une mise en production de démonstration. Il présente un contexte, un enjeu industriel et des éléments de compréhension pour le lecteur.</p>',
            '<p>Dans un environnement marqué par la hausse des coûts logistiques, la disponibilité énergétique et l’exigence qualité, les industriels arbitrent entre investissements immédiats et gains de productivité à moyen terme.</p>',
            '<p>Sur le terrain, les équipes parlent de “petites victoires” : mieux planifier les maintenances, réduire les rebuts, sécuriser les approvisionnements, et améliorer la sécurité. Ces gains cumulés font souvent la différence au bout de quelques mois.</p>',
            '<p>Pour la Côte d’Ivoire, l’enjeu est double : renforcer la compétitivité locale et développer des chaînes de valeur plus intégrées. Cela passe par des investissements ciblés, mais aussi par la gouvernance, les compétences et l’accès à une énergie fiable.</p>',
            '<h3>Ce qu’il faut retenir</h3>',
            '<ul>',
            '<li>Les investissements visent la fiabilité, la compétitivité et la conformité.</li>',
            '<li>La formation et la maintenance deviennent des priorités opérationnelles.</li>',
            '<li>La donnée (KPI, traçabilité) accélère les décisions sur site.</li>',
            '</ul>',
            '<h3>Contexte</h3>',
            '<p>Les entreprises ajustent leurs stratégies selon les secteurs: l’agro mise sur la qualité et le froid, l’énergie sur la performance et la résilience, le BTP sur les matériaux et la disponibilité, et le numérique sur l’automatisation.</p>',
            '<p>Ce contenu reste factice mais volontairement “présentable”: il est écrit pour donner de la matière à la lecture, au référencement et à la navigation du site.</p>',
            '<p>À court terme, le principal défi reste l’exécution: délais, approvisionnement, qualité et sécurité. À long terme, la transformation passe par l’innovation, la montée en compétence et des partenariats solides.</p>',
        ]);

        $blueprints = [
            ['title' => 'Cacao: une nouvelle capacité de conditionnement arrive à Abidjan', 'type' => 'news', 'category' => 'agro-industrie'],
            ['title' => 'Énergie: pourquoi les industriels misent sur le solaire hybride', 'type' => 'analysis', 'category' => 'energie'],
            ['title' => 'Interview: “La qualité export se gagne à l’usine”', 'type' => 'interview', 'category' => 'usines'],
            ['title' => 'Reportage: dans les coulisses d’une zone industrielle en mutation', 'type' => 'report', 'category' => 'zones-industrielles'],
            ['title' => 'Tribune: réindustrialiser, c’est aussi former', 'type' => 'opinion', 'category' => 'industrie-story'],
            ['title' => 'Données: 7 indicateurs pour suivre la performance industrielle', 'type' => 'data', 'category' => 'industrie'],
            ['title' => 'Mines: comment renforcer la sécurité sans ralentir la production', 'type' => 'analysis', 'category' => 'mines'],
            ['title' => 'Logistique: réduire les pertes post-récolte grâce au froid', 'type' => 'news', 'category' => 'transport-et-logistique'],
            ['title' => 'Numérique: cap sur la maintenance prédictive en usine', 'type' => 'analysis', 'category' => 'technologie'],
            ['title' => 'BTP: matériaux locaux et nouvelles contraintes de chantier', 'type' => 'news', 'category' => 'btp'],
            ['title' => 'Chimie & pharma: traçabilité et sérialisation, où en est-on?', 'type' => 'analysis', 'category' => 'pharmaceutique'],
            ['title' => 'Textile: relancer des séries courtes “made in CI”', 'type' => 'report', 'category' => 'textile'],
        ];

        // Dupliquer/varier pour obtenir une homepage plus riche.
        $subCategorySlugs = Category::query()
            ->whereNotNull('parent_id')
            ->pluck('slug')
            ->values()
            ->all();

        for ($i = 1; $i <= 28; $i++) {
            $blueprints[] = [
                'title' => "Industrie: focus investissement & productivité #{$i}",
                'type' => ['news', 'analysis', 'report', 'interview', 'press_release', 'data'][($i - 1) % 6],
                'category' => $subCategorySlugs !== []
                    ? $subCategorySlugs[($i - 1) % count($subCategorySlugs)]
                    : array_values($categories->keys()->all())[($i - 1) % $categories->count()],
            ];
        }

        $created = collect();
        $dayOffset = 0;

        foreach ($blueprints as $idx => $bp) {
            $cover = $covers[$idx % count($covers)];
            $title = $bp['title'];
            $slug = Str::slug($title);
            $category = $categories[$bp['category']] ?? $categories->first();

            $excerpt = implode(' ', [
                "Résumé: {$title}.",
                "Ce papier (texte factice) présente le contexte, les enjeux, les chiffres clés et les impacts possibles pour les entreprises en Côte d’Ivoire.",
                "On y détaille aussi les risques, les opportunités et les pistes d’action pour gagner en productivité.",
            ]);

            $isFeatured = str_starts_with($title, 'Reportage: dans les coulisses d’une zone industrielle en');

            $article = Article::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'signature' => ['Rédaction IIM', 'Équipe Industrie', 'Correspondant Éco', 'Desk Analyse'][($idx) % 4],
                    'excerpt' => $excerpt,
                    'content' => $filler,
                    'cover_image' => $cover['url'],
                    'cover_alt' => $cover['alt'],
                    'status' => 'published',
                    'type' => $bp['type'],
                    'is_featured' => $isFeatured,
                    'is_breaking' => $idx < 6,
                    'is_premium' => $idx % 11 === 0,
                    'view_count' => random_int(120, 14_500),
                    'reading_time' => random_int(3, 9),
                    'published_at' => now()->subDays($dayOffset),
                    'author_id' => $author->id,
                    'category_id' => $category->id,
                    'meta_title' => $title,
                    'meta_description' => Str::limit(strip_tags($excerpt), 150),
                ]
            );

            // Tags & secteurs
            if ($tagIds->isNotEmpty()) {
                $article->tags()->syncWithoutDetaching($tagIds->random(min(4, $tagIds->count()))->all());
            }
            if ($sectorIds->isNotEmpty()) {
                $article->sectors()->syncWithoutDetaching($sectorIds->random(min(2, $sectorIds->count()))->all());
            }

            $created->push($article);
            $dayOffset++;
        }

        // Assurer du contenu pour les rubriques principales (homepage organizer)
        $mustHaveCategorySlugs = [
            'industrie-story',
            'investissement',
            'zones-industrielles',
            'usines',
            'innovation',
            'international',
            'districts',
            'agenda',
            'made-in-ivory-coast',
            '2im-tv',
            'hommes-et-femmes-industriels-ivoiriens',
        ];

        $extraCovers = $covers;
        foreach ($mustHaveCategorySlugs as $i => $slugCat) {
            $cat = $categories->get($slugCat);
            if (! $cat) {
                continue;
            }
            for ($j = 1; $j <= 6; $j++) {
                $title = "{$cat->name} — article démo {$j}";
                $slug = Str::slug($title.'-'.$slugCat.'-'.$j);
                $cover = $extraCovers[($i + $j) % count($extraCovers)];

                $article = Article::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $title,
                        'signature' => ['Rédaction IIM', 'Équipe Industrie', 'Correspondant Éco', 'Desk Analyse'][($j) % 4],
                        'excerpt' => "Dans la rubrique « {$cat->name} », cet article de démonstration apporte du contexte, des enjeux et des pistes d’action. Texte factice, mais structuré pour la présentation et la lecture.",
                        'content' => $filler,
                        'cover_image' => $cover['url'],
                        'cover_alt' => $cover['alt'],
                        'status' => 'published',
                        'type' => 'news',
                        'is_featured' => false,
                        'is_breaking' => false,
                        'is_premium' => false,
                        'view_count' => random_int(80, 9_000),
                        'reading_time' => random_int(3, 10),
                        'published_at' => now()->subDays(random_int(1, 90)),
                        'author_id' => $author->id,
                        'category_id' => $cat->id,
                        'meta_title' => $title,
                        'meta_description' => Str::limit(strip_tags("Rubrique {$cat->name}. ".$title), 150),
                    ]
                );

                if ($tagIds->isNotEmpty()) {
                    $article->tags()->syncWithoutDetaching($tagIds->random(min(4, $tagIds->count()))->all());
                }
                if ($sectorIds->isNotEmpty()) {
                    $article->sectors()->syncWithoutDetaching($sectorIds->random(min(2, $sectorIds->count()))->all());
                }
            }
        }

        // Articles liés (related_articles)
        if ($created->count() >= 6) {
            foreach ($created as $i => $article) {
                $pool = $created->where('id', '!=', $article->id)->pluck('id');
                if ($pool->isEmpty()) {
                    continue;
                }
                $article->related()->syncWithoutDetaching($pool->random(min(3, $pool->count()))->all());
            }
        }
    }
}
