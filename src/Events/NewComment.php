<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Neves\Events\Contracts\TransactionalEvent;
use function Tonysm\TurboLaravel\dom_id;
use Waterhole\Models\Comment;

class NewComment implements ShouldBroadcast, TransactionalEvent
{
    use Dispatchable;
    use InteractsWithSockets;

    protected Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return [
            // TODO: private channel depending on permissions
            new Channel('Waterhole.Models.Channel.'.$this->comment->post->channel->id),
            new Channel('Waterhole.Models.Post.'.$this->comment->post->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'url' => $this->comment->url,
            'dom_id' => dom_id($this->comment),
        ];
    }
}
