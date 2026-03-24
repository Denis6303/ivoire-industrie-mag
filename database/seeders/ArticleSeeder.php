<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
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
        $category = Category::first();

        if (! $author || ! $category) {
            return;
        }

        for ($i = 1; $i <= 12; $i++) {
            $title = "Article démo industrie #{$i}";
            Article::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'excerpt' => 'Résumé court de l’article sur l’actualité industrielle ivoirienne.',
                    'content' => '<p>Contenu éditorial de démonstration pour ivoireindustriemag.</p>',
                    'status' => 'published',
                    'type' => 'news',
                    'is_featured' => $i === 1,
                    'is_breaking' => $i <= 3,
                    'published_at' => now()->subDays($i),
                    'author_id' => $author->id,
                    'category_id' => $category->id,
                ]
            );
        }
    }
}
