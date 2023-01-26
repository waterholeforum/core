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

PostInfo::add('unread', PostUnread::class, position: -100);
PostInfo::add('channel', PostChannel::class, position: -90);
PostInfo::add('locked', PostLocked::class, position: -80);
PostInfo::add('notifications', PostNotifications::class, position: -70);
PostInfo::add('activity', PostActivity::class, position: -60);
PostInfo::add('tags', PostTagsSummary::class, position: -50);
