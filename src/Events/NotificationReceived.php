<?php

namespace Waterhole\Events;

use Illuminate\Support\HtmlString;
use Waterhole\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Views\Components\Alert;
use Waterhole\Views\Components\Notification as NotificationComponent;

class NotificationReceived implements ShouldBroadcast, TransactionalEvent
{
    protected Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('Waterhole.Models.User.'.$this->notification->notifiable->id);
    }

    public function broadcastWith()
    {
        return [
            'html' => render_component(
                (new Alert(
                    type: 'notification',
                    message: new HtmlString(render_component(new NotificationComponent($this->notification))),
                    dismissible: true,
                ))
            ),
            'unreadCount' => $this->notification->notifiable->unread_notification_count,
        ];
    }
}
