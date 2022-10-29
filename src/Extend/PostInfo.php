<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostActivity;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostLocked;
use Waterhole\View\Components\PostNotifications;
use Waterhole\View\Components\PostUnread;

/**
 * A list of components to render under each post's title.
 */
abstract class PostInfo
{
    use OrderedList;
}

PostInfo::add('unread', PostUnread::class);
PostInfo::add('channel', PostChannel::class);
PostInfo::add('locked', PostLocked::class);
PostInfo::add('notifications', PostNotifications::class);
PostInfo::add('activity', PostActivity::class);
