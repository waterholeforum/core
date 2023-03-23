<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Waterhole Admin will be accessible from.
    | Feel free to change this to anything you like.
    |
    */

    'path' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    |
    | Here you may the layout of your dashboard widgets. You're free to
    | use the same widget multiple times in different configurations.
    |
    | Learn more: https://waterhole.dev/docs/dashboard
    */

    'widgets' => [
        [
            'component' => Waterhole\Widgets\GettingStarted::class,
            'width' => 50,
        ],
        [
            'component' => Waterhole\Widgets\Feed::class,
            'width' => 50,
            'title' => 'Waterhole Blog',
            'url' => 'https://waterhole.dev/forum/channels/blog/posts.rss',
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'waterhole::admin.dashboard-users-title',
            'model' => Waterhole\Models\User::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'waterhole::admin.dashboard-posts-title',
            'model' => Waterhole\Models\Post::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'waterhole::admin.dashboard-comments-title',
            'model' => Waterhole\Models\Comment::class,
        ],
    ],
];
