<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Forum Name
    |--------------------------------------------------------------------------
    |
    | This value is the full name of your forum. This value is displayed in the
    | <title> tag, your forum header, and in notification emails.
    |
    */

    'name' => env('APP_NAME', 'Waterhole'),

    /*
    |--------------------------------------------------------------------------
    | Forum Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where your Waterhole forum will be accessible
    | from. Feel free to change this path to anything you like.
    |
    */

    'path' => '',

    /*
    |--------------------------------------------------------------------------
    | Post Filters
    |--------------------------------------------------------------------------
    |
    | Here you can configure which filters are available on the forum index.
    | The first one will be used as the default. This can be overridden for
    | individual channels in the Structure section of the Admin Panel.
    |
    */

    'post_filters' => [
        \Waterhole\Filters\Latest::class,
        \Waterhole\Filters\NewActivity::class,
        \Waterhole\Filters\Top::class,
        \Waterhole\Filters\Oldest::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Post Layout
    |--------------------------------------------------------------------------
    |
    | Here you can specify which post layout to use by default. This can be
    | overridden for individual channels in the Structure section of the
    | Admin Panel.
    |
    | Supported: "list", "cards"
    |
    */

    'default_post_layout' => 'list',

    /*
    |--------------------------------------------------------------------------
    | Posts & Comments Per Page
    |--------------------------------------------------------------------------
    |
    | The numbers of items to show on each page of posts and comments.
    |
    */

    'posts_per_page' => 20,

    'comments_per_page' => 20,

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    |
    |
    */

    'create_per_minute' => 3,

    'search_per_minute' => 10,
];
