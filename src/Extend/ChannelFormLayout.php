<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelFilters;
use Waterhole\Forms\Fields\ChannelLayout;

abstract class ChannelFormLayout
{
    use OrderedList, OfComponents;
}

ChannelFormLayout::add(ChannelLayout::class, 0, 'layout');
ChannelFormLayout::add(ChannelFilters::class, 0, 'filters');
