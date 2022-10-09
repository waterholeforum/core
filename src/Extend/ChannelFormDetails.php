<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelDescription;
use Waterhole\Forms\Fields\ChannelIcon;
use Waterhole\Forms\Fields\ChannelInstructions;
use Waterhole\Forms\Fields\ChannelName;
use Waterhole\Forms\Fields\ChannelSlug;

class ChannelFormDetails
{
    use OrderedList, OfComponents;
}

ChannelFormDetails::add('name', ChannelName::class);
ChannelFormDetails::add('slug', ChannelSlug::class);
ChannelFormDetails::add('icon', ChannelIcon::class);
ChannelFormDetails::add('description', ChannelDescription::class);
ChannelFormDetails::add('instructions', ChannelInstructions::class);
