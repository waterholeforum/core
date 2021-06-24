<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Events;

use Waterhole\Models\CategoryAuthorizer;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Neves\Events\Contracts\TransactionalEvent;

class NewComment implements ShouldBroadcast, TransactionalEvent
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function broadcastOn()
    {
        // Broadcast to channels for all groups who are allowed to see the
        // category which the discussion is in.
        $category = $this->post->discussion->category;
        $groups = app(CategoryAuthorizer::class)->groupsWhoCan('view-discussions', $category);

        return $groups->map(function (Group $group) {
            switch ($group->id) {
                case Group::GUEST_ID:
                    return new Channel('Waterhole.Public');

                case Group::MEMBER_ID:
                    return new PrivateChannel('Waterhole.Members');

                default:
                    return new PrivateChannel('Waterhole.Group.'.$group->id);
            }
        })->all();
    }

    public function broadcastWith()
    {
        return [
            'discussionId' => (string) $this->post->discussion_id,
            'categoryId' => (string) $this->post->discussion->category_id
        ];
    }
}
