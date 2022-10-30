<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\IndexCreatePost;
use Waterhole\View\Components\IndexNav;

/**
 * A list of components to render in the index sidebar.
 */
abstract class IndexSidebar
{
    use OrderedList;
}

IndexSidebar::add('createPost', IndexCreatePost::class);
IndexSidebar::add('nav', IndexNav::class);
