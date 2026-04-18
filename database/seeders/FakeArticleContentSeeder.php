<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeArticleContentSeeder extends Seeder
{
    /**
     * Seed fake editorial content only (articles + comments).
     * Not called by default from DatabaseSeeder.
     */
    public function run(): void
    {
        $this->call([
            ArticleSeeder::class,
            CommentSeeder::class,
        ]);
    }
}

