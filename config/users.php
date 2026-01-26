<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Re-verify After Inactive Days
    |--------------------------------------------------------------------------
    |
    | If set to a number of days, users who have been inactive longer than
    | that will be required to re-verify their email address. Set to null
    | to disable.
    |
    */

    'reverify_after_inactive_days' => 365,

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
        \Waterhole\Filters\Newest::class,
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
        \Waterhole\Filters\Newest::class,
        \Waterhole\Filters\Top::class,
    ],
];
