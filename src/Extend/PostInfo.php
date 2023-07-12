<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\PostActivity;
use Waterhole\View\Components\PostAnswered;
use Waterhole\View\Components\PostChannel;
use Waterhole\View\Components\PostLocked;
use Waterhole\View\Components\PostNotifications;
use Waterhole\View\Components\PostTrash;
use Waterhole\View\Components\PostUnread;

/**
 * A list of components to render under each post's title.
 */
abstract class PostInfo
{
    use OrderedList;
}

PostInfo::add(PostUnread::class, position: -100, key: 'unread');
PostInfo::add(PostTrash::class, position: -95, key: 'trash');
PostInfo::add(PostChannel::class, position: -90, key: 'channel');
PostInfo::add(PostAnswered::class, position: -80, key: 'answered');
PostInfo::add(PostLocked::class, position: -70, key: 'locked');
PostInfo::add(PostNotifications::class, position: -60, key: 'notifications');
PostInfo::add(PostActivity::class, position: -50, key: 'activity');
