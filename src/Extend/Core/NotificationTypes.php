<?php

namespace Waterhole\Extend\Core;

use Waterhole\Extend\Support\UnorderedList;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewComment;
use Waterhole\Notifications\NewFlag;
use Waterhole\Notifications\NewPost;
use Waterhole\Notifications\Reaction;

/**
 * List of notification classes shown in user notification preferences.
 *
 * Use this extender to register notification classes so users can manage
 * them in preferences.
 */
class NotificationTypes extends UnorderedList
{
    public function __construct()
    {
        $this->add(NewPost::class, 'new-post');
        $this->add(NewComment::class, 'new-comment');
        $this->add(Mention::class, 'mention');
        $this->add(Reaction::class, 'reaction');
        $this->add(NewFlag::class, 'new-flag');
    }
}
