<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\Channel as ChannelModel;
use Waterhole\Models\Comment;
use Waterhole\Views\Components\CommentFrame;
use Waterhole\Views\Components\PostCommentsHeading;
use Waterhole\Views\TurboStream;

class NewComment implements ShouldBroadcast, TransactionalEvent
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(protected Comment $comment)
    {
    }

    public function broadcastOn()
    {
        $class = in_array($this->comment->post->channel->id, ChannelModel::allPermitted(null))
            ? Channel::class
            : PrivateChannel::class;

        return [
            new $class($this->comment->post->broadcastChannel()),
            new $class($this->comment->post->channel->broadcastChannel()),
        ];
    }

    public function broadcastWith(): array
    {
        // TODO: we probably can't broadcast the HTML because it won't be translated
        // instead broadcast a URL and load it in a turbo frame?
        return [
            'streams' => implode([
                TurboStream::before(new CommentFrame($this->comment, lazy: true), 'bottom'),
                TurboStream::replace(new PostCommentsHeading($this->comment->post)),
            ]),
        ];
    }
}
