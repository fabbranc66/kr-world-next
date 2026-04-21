<?php

declare(strict_types=1);

return [
    'app_name' => env_value('APP_NAME', 'KR World'),
    'app_env' => env_value('APP_ENV', 'local'),
    'app_debug' => filter_var(env_value('APP_DEBUG', true), FILTER_VALIDATE_BOOL),
    'app_url' => (string) env_value('APP_URL', ''),
    'app_base_path' => (string) env_value('APP_BASE_PATH', ''),
    'lan_host' => (string) env_value('LAN_HOST', ''),
    'database' => [
        'host' => env_value('DB_HOST', '127.0.0.1'),
        'port' => env_value('DB_PORT', '3306'),
        'name' => env_value('DB_NAME', ''),
        'user' => env_value('DB_USER', ''),
        'pass' => env_value('DB_PASS', ''),
    ],
];
