<?php

declare(strict_types=1);

/**
 * App Configuration
 * Edit values here or override via environment variables.
 */
return [
    'debug'    => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN),
    'timezone' => getenv('APP_TIMEZONE') ?: 'UTC',
    'base_url' => getenv('APP_URL') ?: 'http://localhost/portfolio-v2',

    'db' => [
        'host'    => 'localhost',
        'name'    => 'portfolio_db',
        'user'    => 'portfolio',
        'pass'    => 'portfolio2024',
        'charset' => 'utf8mb4',
    ],

    'mail' => [
        'host'     => getenv('MAIL_HOST')     ?: 'smtp.example.com',
        'port'     => (int)(getenv('MAIL_PORT')     ?: 587),
        'user'     => getenv('MAIL_USER')     ?: 'no-reply@example.com',
        'pass'     => getenv('MAIL_PASS')     ?: '',
        'from'     => getenv('MAIL_FROM')     ?: 'no-reply@example.com',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'Portfolio Contact',
        'to'       => getenv('MAIL_TO')       ?: 'admin@example.com',
        'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
    ],

    'security' => [
        'rate_limit_contact' => 5,    // max requests per window
        'rate_limit_window'  => 3600, // 1 hour in seconds
        'csrf_ttl'           => 3600, // 1 hour
    ],

    'pagination' => [
        'projects_per_page' => 6,
    ],

    'supported_langs' => ['es', 'en', 'fr'],
    'default_lang'    => 'es',
];
