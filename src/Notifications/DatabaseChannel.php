<?php

namespace Waterhole\Notifications;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends BaseDatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification): array
    {
        $payload = parent::buildPayload($notifiable, $notification);

        // We will add a few things specific to our notifications system into
        // the database payload, if they are present. See the base Notification
        // class for a description of each of these.
        if (method_exists($notification, 'sender') && $sender = $notification->sender()) {
            $payload['sender_id'] = $sender->getKey();
        }

        if (method_exists($notification, 'group') && $group = $notification->group()) {
            $payload['group_type'] = $group->getMorphClass();
            $payload['group_id'] = $group->getKey();
        }

        if (method_exists($notification, 'content') && $content = $notification->content()) {
            $payload['content_type'] = $content->getMorphClass();
            $payload['content_id'] = $content->getKey();
        }

        return $payload;
    }
}
