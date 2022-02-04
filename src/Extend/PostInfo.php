<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\Views\Components\PostActivity;
use Waterhole\Views\Components\PostChannel;
use Waterhole\Views\Components\PostLocked;
use Waterhole\Views\Components\PostNotifications;
use Waterhole\Views\Components\PostUnread;

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
