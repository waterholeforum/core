<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | By default, Waterhole will use the `web` authentication guard. However,
    | if you want to run Waterhole alongside the default Laravel auth
    | guard, you can configure that below.
    |
    */

    'guard' => 'web',

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

    'password_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Auth Providers
    |--------------------------------------------------------------------------
    |
    | Add the names of the auth providers you want to support. Waterhole will
    | show buttons for these providers on the login and registration pages.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    |
    */

    'providers' => [
        // 'github',
    ],

    /*
    |--------------------------------------------------------------------------
    | Single Sign-On
    |--------------------------------------------------------------------------
    |
    | These settings are to configure the "sso" auth provider. The "url" is
    | your website's URL where Waterhole will send users when they attempt to
    | log in. "secret" is a secret string that will be used to sign payloads
    | used in the process and ensure they are authentic.
    |
    | Learn more: https://waterhole.dev/docs/authentication
    |
    */

    'sso' => [
        'url' => env('WATERHOLE_SSO_URL'),
        'secret' => env('WATERHOLE_SSO_SECRET'),
    ],

];
