<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Waterhole\Models\Channel as ChannelModel;
use Waterhole\Models\Post;

class NewPost implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable;
    use InteractsWithSockets;

    public function __construct(protected Post $post) {}

    public function broadcastOn()
    {
        $class = in_array($this->post->channel->id, ChannelModel::allPermitted(null))
            ? Channel::class
            : PrivateChannel::class;

        return new $class($this->post->channel->broadcastChannel());
    }
}
