<?php

namespace Waterhole\Notifications;

use Waterhole\Events\NotificationReceived;
use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class DatabaseChannel extends BaseDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        /** @var \Waterhole\Models\Notification $model */
        $model = parent::send($notifiable, $notification);

        event(new NotificationReceived($model));

        return $notification;
    }

    protected function buildPayload($notifiable, Notification $notification): array
    {
        $payload = parent::buildPayload($notifiable, $notification);

        // We will add a few things specific to our notifications system into
        // the database payload, if they are present. See the base Notification
        // class for a description of each of these.
        if ($sender = Arr::pull($payload['data'], 'sender')) {
            $payload['sender_id'] = $sender->getKey();
        }

        if ($subject = Arr::pull($payload['data'], 'subject')) {
            $payload['subject_type'] = $subject->getMorphClass();
            $payload['subject_id'] = $subject->getKey();
        }

        if ($content = Arr::pull($payload['data'], 'content')) {
            $payload['content_type'] = $content->getMorphClass();
            $payload['content_id'] = $content->getKey();
        }

        return $payload;
    }
}
