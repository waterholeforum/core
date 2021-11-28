<?php

return [

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
