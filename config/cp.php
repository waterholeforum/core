<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Control Panel Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Control Panel will be accessible from.
    | Feel free to change this to anything you like.
    |
    */

    'path' => 'cp',

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
            'width' => 1 / 2,
        ],
        [
            'component' => Waterhole\Widgets\Feed::class,
            'width' => 1 / 2,
            'title' => 'Waterhole Blog',
            'url' => 'https://waterhole.dev/community/channels/blog/posts.rss',
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 1 / 3,
            'title' => 'waterhole::cp.dashboard-users-title',
            'model' => Waterhole\Models\User::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 1 / 3,
            'title' => 'waterhole::cp.dashboard-posts-title',
            'model' => Waterhole\Models\Post::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 1 / 3,
            'title' => 'waterhole::cp.dashboard-comments-title',
            'model' => Waterhole\Models\Comment::class,
        ],
    ],
];
