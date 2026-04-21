<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => config('app.name'), 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => "Actualités & analyses sur l’industrie en Côte d’Ivoire", 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'contact@ivoireindustriemag.ci', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+225 01 01 151 908', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Abidjan, Côte d’Ivoire', 'group' => 'contact'],
            ['key' => 'social_facebook', 'value' => env('SOCIAL_FACEBOOK_URL', 'https://facebook.com/'), 'group' => 'social'],
            ['key' => 'social_x', 'value' => env('SOCIAL_TWITTER_URL', 'https://x.com/'), 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => env('SOCIAL_LINKEDIN_URL', 'https://linkedin.com/'), 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => env('SOCIAL_INSTAGRAM_URL', 'https://instagram.com/'), 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => env('SOCIAL_YOUTUBE_URL', ''), 'group' => 'social'],
            ['key' => 'homepage_intro', 'value' => "Bienvenue sur Ivoire Industrie Mag. Ici, on suit les investissements, l’innovation et les entreprises qui transforment l’économie.", 'group' => 'homepage'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}

