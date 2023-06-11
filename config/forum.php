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
    | from. Feel free to change this to anything you like.
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
    | individual channels in the Structure section of the Control Panel.
    |
    */

    'post_filters' => [
        \Waterhole\Filters\Latest::class,
        \Waterhole\Filters\Newest::class,
        \Waterhole\Filters\Trending::class,
        \Waterhole\Filters\Top::class,
        \Waterhole\Filters\Oldest::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Post Layouts
    |--------------------------------------------------------------------------
    |
    | Here you can specify which post layout is used on the forum index. This
    | can be overridden for individual channels in the Structure section of
    | the Control Panel.
    |
    */

    'post_layout' => \Waterhole\Layouts\ListLayout::class,

    /*
    |--------------------------------------------------------------------------
    | Posts & Comments Per Page
    |--------------------------------------------------------------------------
    |
    | The numbers of items to show on each page of posts and comments.
    |
    */

    'posts_per_page' => 15,
    'comments_per_page' => 15,

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    |
    | These limits will help to slow down malicious users and spammers.
    | "Create" refers to the creation of new posts and comments.
    |
    */

    'create_per_minute' => 3,
    'search_per_minute' => 10,
];
