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
    | Password Login Enabled
    |--------------------------------------------------------------------------
    |
    | Whether to enable passwords for authentication. If this is false,
    | visitors will only be able to log in and register via OAuth providers.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    |
    */

    'password_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | OAuth Providers
    |--------------------------------------------------------------------------
    |
    | Add the names of the OAuth providers you want to support. Waterhole will
    | show buttons for these providers on the login and registration pages.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    */

    'oauth_providers' => [
        // 'github',
    ],
];
