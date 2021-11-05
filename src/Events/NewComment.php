<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\Comment;

use function Tonysm\TurboLaravel\dom_id;

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
            new PrivateChannel('Waterhole.Models.Channel.'.$this->comment->post->channel->id),
            new PrivateChannel('Waterhole.Models.Post.'.$this->comment->post->id),
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
