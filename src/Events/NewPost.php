<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Neves\Events\Contracts\TransactionalEvent;
use function Tonysm\TurboLaravel\dom_id;
use Waterhole\Models\Post;

class NewPost implements ShouldBroadcast, TransactionalEvent
{
    use Dispatchable;
    use InteractsWithSockets;

    protected Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function broadcastOn()
    {
        return [
            // TODO: private channel depending on permissions
            new Channel('Waterhole.Models.Channel.'.$this->post->channel->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'url' => $this->post->url,
            'dom_id' => dom_id($this->post),
        ];
    }
}
