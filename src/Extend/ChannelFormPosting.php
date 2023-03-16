<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OfComponents;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Forms\Fields\ChannelInstructions;
use Waterhole\Forms\Fields\ChannelSimilarPosts;

abstract class ChannelFormPosting
{
    use OrderedList, OfComponents;
}

ChannelFormPosting::add(ChannelInstructions::class, -100, 'instructions');
ChannelFormPosting::add(ChannelSimilarPosts::class, -90, 'similar-posts');
