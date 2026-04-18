<?php

return [
    'site_name' => env('APP_NAME', 'ivoireindustriemag'),
    'default_locale' => env('APP_LOCALE', 'fr'),
    'supported_locales' => ['fr', 'en'],
    /*
     * Longueur max du chapô « à la une » (accueil), en caractères Unicode.
     * Référence : « Tu es un développeur senior spécialisé en architecture web et en refactoring
     * de projets Laravel. Le projet sur lequel tu travailles est un site web d'information nommé
     * ivoireindustriemag.com, » → 191 caractères.
     */
    'featured_excerpt_max_chars' => 191,

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

    /*
     * Page rubrique « 2IM TV » : intégration YouTube (lecteur + option playlist).
     * playlist_id : ID de playlist (recommandé pour un embed fiable type « chaîne »).
     * channel_id : utile surtout pour liens / futures extensions ; l’embed listing complet
     *               nécessite en pratique une playlist ou des IDs vidéo.
     */
    'youtube' => [
        'channel_id' => env('YOUTUBE_CHANNEL_ID'),
        'playlist_id' => env('YOUTUBE_PLAYLIST_ID'),
        'channel_url' => env('SOCIAL_YOUTUBE_URL', '#'),
    ],
];
