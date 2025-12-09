<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Guard
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'viajero' => [
            'driver' => 'session',
            'provider' => 'viajeros',
        ],

        'hotel' => [
            'driver' => 'session',
            'provider' => 'hotels',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'viajeros' => [
            'driver' => 'eloquent',
            'model' => App\Models\Viajero::class,
        ],

        'hotels' => [
            'driver' => 'eloquent',
            'model' => App\Models\Hotel::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
