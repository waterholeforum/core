<?php

return [

    'widgets' => [
        [
            'component' => Waterhole\Widgets\GettingStarted::class,
            'width' => 100,
        ],
        [
            'component' => Waterhole\Widgets\Feed::class,
            'width' => 50,
            'url' => 'https://www.nasa.gov/rss/dyn/lg_image_of_the_day.rss',
        ],
        [
            'component' => Waterhole\Widgets\Feed::class,
            'width' => 50,
            'url' => 'https://www.nasa.gov/rss/dyn/lg_image_of_the_day.rss',
        ],
    ],

];
