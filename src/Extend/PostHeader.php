<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostAttribution;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostTagsSummary;
use Waterhole\View\Components\PostTitle;

/**
 * A list of components to render in the post page header.
 */
abstract class PostHeader
{
    use OrderedList;
}

PostHeader::add('channel', PostChannel::class, position: -100);
PostHeader::add('tags', PostTagsSummary::class, position: -90);
PostHeader::add('title', PostTitle::class, position: -80);
PostHeader::add('attribution', PostAttribution::class, position: -70);
