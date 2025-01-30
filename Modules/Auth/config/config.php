<?php

return [
    'name' => 'Auth',
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token', // or 'passport' or 'sanctum', depending on your setup
            'provider' => 'users',
        ],
    ],
];
