<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostAttribution;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostTagsSummary;
use Waterhole\View\Components\PostTitle;
use Waterhole\View\Components\PostTrash;

/**
 * A list of components to render in the post page header.
 */
abstract class PostHeader
{
    use OrderedList;
}

PostHeader::add(PostTrash::class, position: -110, key: 'trash');
PostHeader::add(PostChannel::class, position: -100, key: 'channel');
PostHeader::add(PostTagsSummary::class, position: -90, key: 'tags');
PostHeader::add(PostAttribution::class, position: -80, key: 'attribution');
PostHeader::add(PostTitle::class, position: -70, key: 'title');
