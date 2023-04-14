<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\UnorderedList;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewComment;
use Waterhole\Notifications\NewPost;

/**
 * A list of notification types to offer user preferences for.
 */
abstract class NotificationTypes
{
    use UnorderedList;
}

NotificationTypes::add(NewPost::class, 'new-post');
NotificationTypes::add(NewComment::class, 'new-comment');
NotificationTypes::add(Mention::class, 'mention');
