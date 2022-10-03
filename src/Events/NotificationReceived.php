<?php

namespace Waterhole\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Neves\Events\Contracts\TransactionalEvent;
use Waterhole\Models\Notification;
use Waterhole\Views\Components\Alert;
use Waterhole\Views\Components\Notification as NotificationComponent;

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
        // TODO: we probably can't broadcast the HTML because it won't be translated
        // instead broadcast a URL and load it in a turbo frame?
        return [
            'html' => Blade::renderComponent(
                new Alert(
                    type: 'notification',
                    message: new HtmlString(
                        Blade::renderComponent(new NotificationComponent($this->notification)),
                    ),
                    dismissible: true,
                ),
            ),
            'unreadCount' => $this->notification->notifiable->unread_notification_count,
        ];
    }
}
