<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelAnswers;
use Waterhole\Forms\Fields\ChannelReactions;
use Waterhole\Forms\Fields\ChannelTaxonomies;

abstract class ChannelFormFeatures
{
    use OrderedList, OfComponents;
}

ChannelFormFeatures::add(ChannelTaxonomies::class, 0, 'taxonomies');
ChannelFormFeatures::add(ChannelAnswers::class, 0, 'answers');
ChannelFormFeatures::add(ChannelReactions::class, 0, 'reactions');
