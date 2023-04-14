<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\Set;
use Waterhole\Layouts\CardsLayout;
use Waterhole\Layouts\ListLayout;

/**
 * A list of layouts that can be applied to post feeds.
 */
abstract class PostLayouts
{
    use Set;
}

PostLayouts::add(ListLayout::class);
PostLayouts::add(CardsLayout::class);
