<?php

return [
     /*
    |--------------------------------------------------------------------------
    | Default Mail Configuration
    |--------------------------------------------------------------------------
    */
    'form' =>[
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FORM_NAME', 'Example'),
    ],
    /*
    |--------------------------------------------------------------------------
    | Template Configuration
    |--------------------------------------------------------------------------
    */
    'templates' => [
        'path' => 'emails',
        'cache' => true,
        'default_layout' => 'emails.layouts.default',
    ],
/*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    */
    'retry' => [
        'attempts' => env('EMAIL_RETRY_ATTEMPTS', 3),
        'delay' => env('EMAIL_RETRY_DELAY', 5), // in seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'channel' => env('EMAIL_LOG_CHANNEL', 'email'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'enabled' => true,
        'connection' => env('EMAIL_QUEUE_CONNECTION', 'redis'),
        'queue' => env('EMAIL_QUEUE_NAME', 'emails'),
    ],

];