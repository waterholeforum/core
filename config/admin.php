<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Waterhole Admin Panel will be accessible
    | from. Feel free to change this path to anything you like.
    |
    */

    'path' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    |
    | Here you may define any number of dashboard widgets. You're free to
    | use the same widget multiple times in different configurations.
    |
    */

    'widgets' => [
        [
            'component' => Waterhole\Widgets\GettingStarted::class,
            'width' => 50,
        ],
        [
            'component' => Waterhole\Widgets\Feed::class,
            'width' => 50,
            'url' => 'https://www.nasa.gov/rss/dyn/lg_image_of_the_day.rss',
            'limit' => 4,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'Users',
            'model' => Waterhole\Models\User::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'Posts',
            'model' => Waterhole\Models\Post::class,
        ],
        [
            'component' => Waterhole\Widgets\LineChart::class,
            'width' => 100 / 3,
            'title' => 'Comments',
            'model' => Waterhole\Models\Comment::class,
        ],
    ],

];
