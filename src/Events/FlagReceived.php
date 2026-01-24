<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\User;
use Waterhole\View\Components\ModerationBadge;
use Waterhole\View\TurboStream;

class FlagReceived implements ShouldBroadcast, TransactionalEvent
{
    public function __construct(protected User $user)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel($this->user->broadcastChannel());
    }

    public function broadcastWith(): array
    {
        return [
            'streams' => TurboStream::replace(new ModerationBadge($this->user)),
        ];
    }

}
