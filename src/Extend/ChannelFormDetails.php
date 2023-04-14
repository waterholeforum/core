<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelDescription;
use Waterhole\Forms\Fields\ChannelIgnore;
use Waterhole\Forms\Fields\ChannelName;
use Waterhole\Forms\Fields\ChannelSlug;
use Waterhole\Forms\Fields\Icon;

abstract class ChannelFormDetails
{
    use OrderedList, OfComponents;
}

ChannelFormDetails::add(ChannelName::class, 0, 'name');
ChannelFormDetails::add(ChannelSlug::class, 0, 'slug');
ChannelFormDetails::add(Icon::class, 0, 'icon');
ChannelFormDetails::add(ChannelDescription::class, 0, 'description');
ChannelFormDetails::add(ChannelIgnore::class, 0, 'ignore');
