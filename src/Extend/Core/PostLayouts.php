<?php

namespace Waterhole\Extend\Core;

use Waterhole\Extend\Support\Set;
use Waterhole\Layouts\CardsLayout;
use Waterhole\Layouts\ListLayout;

/**
 * Post feed layout classes.
 *
 * Used when rendering feeds and when configuring channel layouts in the
 * control panel.
 */
class PostLayouts extends Set
{
    public function __construct()
    {
        $this->add(ListLayout::class);
        $this->add(CardsLayout::class);
    }
}
