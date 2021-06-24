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

use Waterhole\Notification;
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
