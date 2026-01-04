<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Description
    |--------------------------------------------------------------------------
    |
    | Used when a page-specific description is not available.
    |
    */
    'default_description' => null,

    /*
    |--------------------------------------------------------------------------
    | Default OpenGraph Image
    |--------------------------------------------------------------------------
    |
    | Absolute URL to a fallback image for social previews.
    |
    */
    'default_og_image' => null,

    /*
    |--------------------------------------------------------------------------
    | Nofollow Allowlist
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of domains that should not receive "nofollow".
    |
    */
    'nofollow_allow' => [],

    /*
    |--------------------------------------------------------------------------
    | Nofollow Rel Attribute
    |--------------------------------------------------------------------------
    |
    | The rel attribute to apply to external links not on the allowlist.
    |
    */
    'nofollow_rel' => 'nofollow ugc',
];
