<?php

return [

    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'path' => env('PUSHER_APP_PATH', ''),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],

    'dashboard' => [
        'port' => env('WEBSOCKETS_PORT', 6001),
    ],

    'allowed_origins' => [
        '*', // Здесь вы можете ограничить источники, если нужно
    ],

    'max_request_size_in_kb' => 250,

    'path' => 'laravel-websockets',

    'statistics' => [
        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,
        'interval_in_seconds' => 60,
        'delete_statistics_older_than_days' => 60,
        'perform_dns_lookup' => true,
    ],

];
