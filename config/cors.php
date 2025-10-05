<?php

return [

    // Inclua as rotas da API e do CSRF
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'api/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Origem do seu front (pode ser múltiplas, separadas por vírgula no .env)
    // Ex.: FRONTEND_ORIGINS=http://localhost:5173,http://127.0.0.1:5173,https://app.seu-dominio.com
    'allowed_origins' => explode(',', env('FRONTEND_ORIGINS', 'http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Para cookies (Sanctum SPA) precisa ser true
    'supports_credentials' => true,
];
