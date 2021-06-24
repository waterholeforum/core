<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostActivity;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostLocked;
use Waterhole\View\Components\PostNotifications;
use Waterhole\View\Components\PostTagsSummary;
use Waterhole\View\Components\PostUnread;

/**
 * A list of components to render under each post's title.
 */
abstract class PostInfo
{
    use OrderedList;
}

PostInfo::add(PostUnread::class, position: -100, key: 'unread');
PostInfo::add(PostChannel::class, position: -90, key: 'channel');
PostInfo::add(PostLocked::class, position: -80, key: 'locked');
PostInfo::add(PostNotifications::class, position: -70, key: 'notifications');
PostInfo::add(PostActivity::class, position: -60, key: 'activity');
PostInfo::add(PostTagsSummary::class, position: -50, key: 'tags');
