<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Notifications;

use Waterhole\Models\Post;
use Waterhole\Models\Reaction as ReactionModel;

class Reaction extends Notification
{
    /**
     * @var Post
     */
    protected $reaction;

    public function __construct(ReactionModel $reaction)
    {
        $this->reaction = $reaction;
    }

    public function sender($notifiable)
    {
        return $this->reaction->user;
    }

    public function subject($notifiable)
    {
        return $this->reaction->post;
    }

    public function content($notifiable)
    {
        return $this->reaction;
    }
}
