<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Automatic translation provider (LibreTranslate-compatible API)
    |--------------------------------------------------------------------------
    */
    'provider' => env('AUTO_TRANSLATE_PROVIDER', 'google_free'),
    'endpoint' => env('AUTO_TRANSLATE_ENDPOINT', 'https://libretranslate.com/translate'),
    'api_key' => env('AUTO_TRANSLATE_API_KEY'),
    'timeout_seconds' => (int) env('AUTO_TRANSLATE_TIMEOUT', 8),
    'verify_ssl' => filter_var(env('AUTO_TRANSLATE_VERIFY_SSL', false), FILTER_VALIDATE_BOOL),
];
