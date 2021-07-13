<?php

return [
    'database' => [
        'type' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'beatstudio',
        'username' => 'root',
        'password' => 'B34tsp1n',
        'engine' => 'InnoDB',
        'charset' => 'utf8mb4',
        // Remove 'host' above when using sockets
        'socket' => '',
    ],

    'cors' => [
        'same_site' => 'lax',
        'enabled' => true,
        'origin' => array (
  0 => '*',
),
        'methods' => array (
  0 => 'GET',
  1 => 'POST',
  2 => 'PUT',
  3 => 'PATCH',
  4 => 'DELETE',
  5 => 'HEAD',
),
        'headers' => array (
),
        'exposed_headers' => array (
),
        'max_age' => 600,
        'credentials' => true,
    ],

    'rate_limit' => [
        'enabled' => false,
        'limit' => 100,
        'interval' => 60,
        'adapter' => 'redis',
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 10,
    ],

    'storage' => [
        'adapter' => 'local',
        'root' => 'public/uploads/beatstudio/originals',
        'root_url' => '/uploads/beatstudio/originals',
        'thumb_root' => 'public/uploads/beatstudio/generated',
        // 'key' => '',
        // 'secret' => '',
        // 'region' => '',
        // 'version' => '',
        // 'bucket' => '',
        // 'options' => '',
        // 'endpoint' => '',
        // 'proxy_downloads' => '',
    ],

    'mail' => [
        'default' => [
            'transport' => 'sendmail',
            // 'sendmail' => '',
            // 'host' => '',
            // 'port' => '',
            // 'username' => '',
            // 'password' => '',
            // 'encryption' => '',
            'from' => 'admin@example.com'
        ],
    ],

    'cache' => [
        'enabled' => false,
        'response_ttl' => 3600,
        'pool' => [
            // 'adapter' => '',
            // 'path' => '',
            // 'host' => '',
            // 'port' => '',
        ],
    ],

    'auth' => [
        'secret_key' => 'yhfgLTYoQdXWfmJIDUO9Llm8BkGnra5v',
        'public_key' => 'b5d2fe69-072c-4461-aa8c-73f0decbbb0f',
        'social_providers' => [
            // 'okta' => '',
            // 'github' => '',
            // 'facebook' => '',
            // 'google' => '',
            // 'twitter' => '',
        ],
    ],

    'hooks' => [
        'actions' => [],
        'filters' => [],
    ],

    'tableBlacklist' => [],

    'env' => 'production',

    'logger' => [
        'path' => 'C:\Users\xamp\htdocs\directus\src\core\Directus\Util\Installation/../../../../../logs',
    ],
];
