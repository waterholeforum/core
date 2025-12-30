<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JSON:API Enabled
    |--------------------------------------------------------------------------
    |
    | The Waterhole JSON:API allows forum data to be accessed through an API
    | that conforms to the JSON:API specification (https://jsonapi.org).
    |
    | Learn more: https://waterhole.dev/docs/api
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | JSON:API Public
    |--------------------------------------------------------------------------
    |
    | This option makes the Waterhole JSON:API public, so anyone will be able
    | to access it, and users will be able to generate API tokens in their
    | account preferences. If disabled, the API will require authentication
    | using API tokens which only administrators will be able to create.
    |
    | Learn more: https://waterhole.dev/docs/api
    |
    */

    'public' => true,

    /*
    |--------------------------------------------------------------------------
    | JSON:API Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Waterhole JSON:API will be accessible
    | from. Feel free to change this to anything you like.
    |
    */

    'path' => 'api',

];
