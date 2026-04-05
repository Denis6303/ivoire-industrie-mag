<?php

return [
    'site_name' => env('APP_NAME', 'ivoireindustriemag'),
    'default_locale' => env('APP_LOCALE', 'fr'),
    'supported_locales' => ['fr', 'en'],
    'articles_per_page' => 12,
    'companies_per_page' => 12,
    'projects_per_page' => 12,
    'social' => [
        'facebook' => [
            'url' => env('SOCIAL_FACEBOOK_URL', '#'),
            'count' => env('SOCIAL_FACEBOOK_COUNT', '2.5k'),
        ],
        'twitter' => [
            'url' => env('SOCIAL_TWITTER_URL', '#'),
            'count' => env('SOCIAL_TWITTER_COUNT', '1.2k'),
        ],
        'youtube' => [
            'url' => env('SOCIAL_YOUTUBE_URL', '#'),
            'count' => env('SOCIAL_YOUTUBE_COUNT', '890'),
        ],
        'instagram' => [
            'url' => env('SOCIAL_INSTAGRAM_URL', '#'),
            'count' => env('SOCIAL_INSTAGRAM_COUNT', '3.1k'),
        ],
    ],
];
