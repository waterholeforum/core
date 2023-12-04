<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allow Registration
    |--------------------------------------------------------------------------
    |
    | Whether to allow visitors to register new user accounts. If this
    | is false, the registration page will become inaccessible and the
    | "sign up" link will be hidden.
    |
    */

    'allow_registration' => true,

    /*
    |--------------------------------------------------------------------------
    | Password Authentication Enabled
    |--------------------------------------------------------------------------
    |
    | Whether to enable passwords for authentication. If this is false,
    | visitors will only be able to log in and register via auth providers.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    |
    */

    'password_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Auth Providers
    |--------------------------------------------------------------------------
    |
    | Add the names of the auth providers you want to support. Waterhole will
    | show buttons for these providers on the login and registration pages.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    */

    'providers' => [
        // 'github',
    ],

    /*
    |--------------------------------------------------------------------------
    | Single Sign-On
    |--------------------------------------------------------------------------
    |
    |
    |
    | Learn more: https://waterhole.dev/docs/authentication
    */

    'sso' => [
        'url' => env('WATERHOLE_SSO_URL'),
        'secret' => env('WATERHOLE_SSO_SECRET'),
    ],
];
