<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Waterhole\Models\Comment;
use Waterhole\View\Components\CommentFrame;
use Waterhole\View\TurboStream;

class NewComment implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(protected Comment $comment) {}

    public function broadcastOn()
    {
        $class = $this->comment->post->channel->isPublic() ? Channel::class : PrivateChannel::class;

        return [
            new $class($this->comment->post->broadcastChannel()),
            new $class($this->comment->post->channel->broadcastChannel()),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'streams' => TurboStream::append(
                (new CommentFrame($this->comment, lazy: true))->withAttributes([
                    'class' => 'card__row',
                ]),
                '.comment-list',
            ),
        ];
    }
}
