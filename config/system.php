<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    |
    | The site key for the corresponding domain from your Waterhole account.
    | Without a key entered, your forum is considered to be in Trial Mode and
    | you cannot make it available on a public domain.
    |
    */

    'site_key' => env('WATERHOLE_SITE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Echo Configuration
    |--------------------------------------------------------------------------
    |
    | This is the configuration that will be passed in when Waterhole's
    | frontend JavaScript sets up the Laravel Echo client.
    |
    */

    'echo_config' => [
        'broadcaster' => env('BROADCAST_DRIVER') === 'pusher' ? 'pusher' : null,
        'key' => env('PUSHER_APP_KEY'),
        'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Extensions Path
    |--------------------------------------------------------------------------
    |
    | When generating addons via `php artisan make:waterhole-extension`, this
    | path will be used by default. You can still specify custom repository
    | paths in your composer.json, but this is the path used by the generator.
    |
    */

    'extensions_path' => base_path('extensions'),

    /*
    |--------------------------------------------------------------------------
    | Send the Powered-By Header
    |--------------------------------------------------------------------------
    |
    | Websites like builtwith.com use the X-Powered-By header to determine
    | what technologies are used on a particular site. By default, we'll
    | send this header, but feel free to disable it.
    |
    */

    'send_powered_by_header' => true,

    /*
    |--------------------------------------------------------------------------
    | Intensive Operations
    |--------------------------------------------------------------------------
    |
    | Sometimes Waterhole requires extra resources to complete intensive
    | operations. Here you may configure these system resource limits.
    |
    */

    'php_memory_limit' => '-1',
    'php_max_execution_time' => '-1',
];
