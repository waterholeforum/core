<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth Enabled
    |--------------------------------------------------------------------------
    |
    | Use this option to enable OAuth integration in Waterhole. When turned on,
    | Waterhole will allow logins and registrations to take place through the
    | providers configured below.
    |
    */

    'enabled' => env('WATERHOLE_OAUTH_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | OAuth Providers
    |--------------------------------------------------------------------------
    |
    | Add the names of the OAuth providers you want to support. Waterhole will
    | show buttons for these providers on the login and registration pages.
    |
    */

    'providers' => [
        // 'github',
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Routes
    |--------------------------------------------------------------------------
    |
    | These are the routes that Waterhole will register to handle OAuth login
    | requests and callbacks.
    |
    */

    'routes' => [
        'login' => 'oauth/{provider}',
        'callback' => 'oauth/{provider}/callback',
    ],

];
