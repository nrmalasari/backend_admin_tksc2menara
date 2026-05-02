<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:3000',
        'https://portal.tksc2menara.dpdns.org', // FRONTEND DOMAIN
        'https://admin.tksc2menara.dpdns.org',  // BACKEND DOMAIN
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true, // HARUS TRUE untuk auth token
];