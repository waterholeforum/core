<?php

return [

    /*
    |--------------------------------------------------------------------------
    | License Key
    |--------------------------------------------------------------------------
    |
    | The license key for the corresponding domain from your Waterhole account.
    | Without a key entered, your forum will considered to be in Trial Mode.
    |
    */

    'license_key' => env('WATERHOLE_LICENSE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Extensions Paths
    |--------------------------------------------------------------------------
    |
    | When generating addons via `php waterhole make:extension`, this path will be
    | used by default. You can still specify custom repository paths in
    | your composer.json, but this is the path used by the generator.
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
    | send this header, but you are absolutely allowed to disable it.
    |
    */

    'send_powered_by_header' => true,

];
