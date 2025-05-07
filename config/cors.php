<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Cross-Origin Resource Sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],  // Apply CORS to all routes under /api/
    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, PUT, etc.)
    'allowed_origins' => ['*'], //  Allow requests from any origin (for development, use specific origins in production)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 3600,       // Cache preflight requests for 1 hour (optional)
    'supports_credentials' => false, //  If your API uses cookies, set this to true
];
