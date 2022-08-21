<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | By default, Waterhole will use the `web` authentication guard to
    | integrate nicely with your Laravel application. However, if you want
    | Waterhole's authentication to work independently, you can configure that.
    |
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Post Filters
    |--------------------------------------------------------------------------
    |
    | Here you can configure which filters are available when viewing a user's.
    | posts on their profile page. The first one will be used as the default.
    |
    */

    'post_filters' => [
        \Waterhole\Filters\Latest::class,
        \Waterhole\Filters\Top::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Comment Filters
    |--------------------------------------------------------------------------
    |
    | Here you can configure which filters are available when viewing a user's.
    | comments on their profile page. The first one will be used as the default.
    |
    */

    'comment_filters' => [
        \Waterhole\Filters\Latest::class,
        \Waterhole\Filters\Top::class,
    ],
];
