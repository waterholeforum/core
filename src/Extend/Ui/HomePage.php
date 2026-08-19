<?php

namespace Waterhole\Extend\Ui;

use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\HomeFeed;

/**
 * Components rendered on the forum home page.
 *
 * Use this extender to add, remove, replace, or reorder home page components.
 */
class HomePage extends ComponentList
{
    public function __construct()
    {
        $this->add(HomeFeed::class, 'feed');
    }
}
