<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Automatic translation provider (LibreTranslate-compatible API)
    |--------------------------------------------------------------------------
    */
    'provider' => env('AUTO_TRANSLATE_PROVIDER', 'auto'),
    'endpoint' => env('AUTO_TRANSLATE_ENDPOINT', 'https://libretranslate.com/translate'),
    'api_key' => env('AUTO_TRANSLATE_API_KEY'),
    'timeout_seconds' => (int) env('AUTO_TRANSLATE_TIMEOUT', 6),
    'verify_ssl' => filter_var(env('AUTO_TRANSLATE_VERIFY_SSL', false), FILTER_VALIDATE_BOOL),
    'request_delay_ms' => (int) env('AUTO_TRANSLATE_DELAY_MS', 150),
    'retries' => (int) env('AUTO_TRANSLATE_RETRIES', 1),
];
