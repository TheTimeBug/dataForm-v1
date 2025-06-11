<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorization Cache Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for authorization system caching
    |
    */

    // Cache TTL in seconds (5 minutes default)
    'cache_ttl' => env('AUTH_CACHE_TTL', 300),

    // Enable/disable authorization caching
    'cache_enabled' => env('AUTH_CACHE_ENABLED', true),

    // Cache key prefix
    'cache_prefix' => 'auth_',

    // Performance monitoring settings
    'performance' => [
        'log_slow_requests' => env('LOG_SLOW_REQUESTS', true),
        'slow_request_threshold' => env('SLOW_REQUEST_THRESHOLD', 1000), // milliseconds
        'enable_debug_headers' => env('ENABLE_DEBUG_HEADERS', true),
    ],
]; 