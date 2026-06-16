<?php

return [
    'domain' => env('HORIZON_DOMAIN', null),

    'path' => env('HORIZON_PATH', 'horizon'),

    'use' => 'default',

    'middleware' => ['web', 'auth'],

    'waits' => [
        'redis:default' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 1440,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    'metrics' => [
        'trim_snapshots' => 24,
    ],

    'fast_termination' => false,

    'memory_limit' => 64,

    'defaults' => [
        'supervisor-1' => [
            'connection' => env('QUEUE_CONNECTION', 'redis'),
            'queue' => ['default', 'emails'],
            'balance' => 'auto',
            'maxProcesses' => 2,
            'maxTime' => 0,
            'maxJobs' => 0,
            'memory' => 128,
            'tries' => 3,
            'timeout' => 120,
            'nice' => 0,
        ],
    ],

    'environments' => [
        'production' => [
            'supervisor-1' => [
                'maxProcesses' => (int) env('HORIZON_PROCESSES', 4),
                'balance' => 'auto',
            ],
        ],
        'local' => [
            'supervisor-1' => [
                'maxProcesses' => 1,
            ],
        ],
    ],

    'auth' => [
        'gate' => 'viewHorizon',
    ],

    'notifications' => [
        'slack' => [
            'webhook_url' => env('HORIZON_SLACK_WEBHOOK'),
            'channel' => env('HORIZON_SLACK_CHANNEL', null),
            'username' => env('HORIZON_SLACK_USERNAME', 'Horizon'),
            'emoji' => env('HORIZON_SLACK_EMOJI', ':rocket:'),
            'queue_waits' => [
                'redis:default' => 60,
            ],
        ],
    ],
];
