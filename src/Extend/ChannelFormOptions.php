<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelAnswers;
use Waterhole\Forms\Fields\ChannelDefaultLayout;
use Waterhole\Forms\Fields\ChannelFilters;
use Waterhole\Forms\Fields\ChannelReactions;
use Waterhole\Forms\Fields\ChannelSandbox;
use Waterhole\Forms\Fields\ChannelTaxonomies;

abstract class ChannelFormOptions
{
    use OrderedList, OfComponents;
}

ChannelFormOptions::add(ChannelSandbox::class, 0, 'sandbox');
ChannelFormOptions::add(ChannelDefaultLayout::class, 0, 'default-layout');
ChannelFormOptions::add(ChannelFilters::class, 0, 'filters');
ChannelFormOptions::add(ChannelTaxonomies::class, 0, 'taxonomies');
ChannelFormOptions::add(ChannelAnswers::class, 0, 'answers');
ChannelFormOptions::add(ChannelReactions::class, 0, 'reactions');
