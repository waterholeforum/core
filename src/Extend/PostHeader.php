<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\PostAttribution;
use Waterhole\Views\Components\PostChannel;
use Waterhole\Views\Components\PostTitle;

/**
 * A list of components to render in the post page header.
 */
abstract class PostHeader
{
    use OrderedList;
}

PostHeader::add('channel', PostChannel::class);
PostHeader::add('title', PostTitle::class);
PostHeader::add('attribution', PostAttribution::class);
