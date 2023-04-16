<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\Notification;
use Waterhole\View\Components\NotificationAlert;
use Waterhole\View\Components\NotificationsBadge;
use Waterhole\View\TurboStream;

class NotificationReceived implements ShouldBroadcast, TransactionalEvent
{
    public function __construct(protected Notification $notification)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->notification->notifiable->broadcastChannel());
    }

    public function broadcastWith()
    {
        return [
            'streams' => implode([
                TurboStream::append(new NotificationAlert($this->notification), '#alerts'),
                TurboStream::replace(new NotificationsBadge($this->notification->notifiable)),
            ]),
        ];
    }
}
