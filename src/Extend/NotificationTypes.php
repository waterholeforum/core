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

NotificationTypes::add('new-post', NewPost::class);
NotificationTypes::add('new-comment', NewComment::class);
NotificationTypes::add('mention', Mention::class);
