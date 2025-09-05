<?php

return [
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'api_key'  => env('OPENAI_API_KEY', ''),

    'defaults' => [
        'timeout_seconds'  => (int) env('OPENAI_TIMEOUT', 20),
    ],

    // Allowed models + optional capabilities (future use)
    'models' => [
        'gpt-4o-mini'  => ['reasoning' => false],
        'gpt-4.1-mini' => ['reasoning' => false],
        'o3-mini'      => ['reasoning' => true],
    ],
];
