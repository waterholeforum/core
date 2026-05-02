<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | This option allows you to configure which theme Waterhole uses. A null
    | value means that Waterhole will allow users to toggle between light
    | and dark mode. You can disable this toggle by setting this to either
    | "light" or "dark".
    |
    */

    'theme' => null,

    /*
    |--------------------------------------------------------------------------
    | Global Sidebar
    |--------------------------------------------------------------------------
    |
    | Enable a global forum navigation sidebar across forum pages. When
    | disabled, the sidebar is only shown on the main index-style pages.
    |
    */

    'global_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | Emoji URL
    |--------------------------------------------------------------------------
    |
    | This URL will be used to output emoji. You can set this to null to
    | disable emoji parsing and fall back to system emoji.
    |
    | Learn more: https://s9etextformatter.readthedocs.io/Plugins/Emoji/Synopsis/
    |
    */

    'emoji_url' => 'https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/{@tseq}.svg',
];
