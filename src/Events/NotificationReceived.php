<?php

namespace Waterhole\Events;

use Waterhole\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Neves\Events\Contracts\TransactionalEvent;

class NotificationReceived implements ShouldBroadcast, TransactionalEvent
{
    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Waterhole.User.'.$this->notification->notifiable->id);
    }

    public function broadcastWith()
    {
        return [
            'notificationId' => $this->notification->id
        ];
    }
}
