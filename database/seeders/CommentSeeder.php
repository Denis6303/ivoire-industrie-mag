<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::query()->published()->latest('published_at')->take(12)->get();
        if ($articles->isEmpty()) {
            return;
        }

        $admin = User::query()->where('email', 'admin@ivoireindustriemag.ci')->first();

        $guestPool = [
            ['name' => 'Koffi', 'email' => 'koffi@example.com'],
            ['name' => 'Awa', 'email' => 'awa@example.com'],
            ['name' => 'Jean', 'email' => 'jean@example.com'],
            ['name' => 'Fatou', 'email' => 'fatou@example.com'],
        ];

        foreach ($articles as $article) {
            $count = random_int(1, 4);
            for ($i = 0; $i < $count; $i++) {
                $isGuest = random_int(0, 1) === 1;
                $guest = $guestPool[array_rand($guestPool)];

                Comment::updateOrCreate(
                    [
                        'article_id' => $article->id,
                        'content' => "Commentaire de démonstration #".($i + 1)." sur « {$article->title} ».",
                    ],
                    [
                        'article_id' => $article->id,
                        'content' => "Commentaire de démonstration #".($i + 1)." sur « {$article->title} ».\n\nTexte factice: avis, question, réaction, et proposition de sujet.",
                        'is_approved' => true,
                        'guest_name' => $isGuest ? $guest['name'] : null,
                        'guest_email' => $isGuest ? $guest['email'] : null,
                        'user_id' => $isGuest ? null : ($admin?->id),
                        'parent_id' => null,
                        'created_at' => now()->subDays(random_int(0, 20))->subMinutes(random_int(0, 600)),
                    ]
                );
            }
        }
    }
}

