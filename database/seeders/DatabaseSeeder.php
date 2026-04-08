<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            IndustrySectorSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            SiteSettingSeeder::class,
            CompanySeeder::class,
            IndustrialProjectSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            NewsletterSubscriptionSeeder::class,
            MediaSeeder::class,
        ]);
    }
}
