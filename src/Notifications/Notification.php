<?php

namespace Waterhole\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\URL;
use Waterhole\Models\User;

abstract class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return array_merge(
            ['database'],
            $notifiable->notification_channels[get_class($this)] ?? []
        );
    }

    public function toArray($notifiable)
    {
        return [
            'sender' => $this->sender(),
            'subject' => $this->subject(),
            'content' => $this->content(),
        ];
    }

    public function toMail($notifiable)
    {
        $html = Markdown::parse($this->title());

        return (new MailMessage())
            ->subject(strip_tags($html))
            ->markdown('waterhole::mail.notification', [
                'notification' => $this,
                'avatar' => $this->sender()->avatar,
                'name' => $this->sender()->name,
                'html' => $html,
                'actionText' => $this->button(),
                'actionUrl' => $this->url(),
                'excerpt' => $this->excerpt(),
                'reason' => $this->reason(),
                'unsubscribeText' => $this->unsubscribeText(),
                'unsubscribeUrl' => URL::signedRoute('waterhole.notifications.unsubscribe', [
                    'type' => get_class($this),
                    'notifiable_type' => $notifiable->getMorphClass(),
                    'notifiable_id' => $notifiable->getKey(),
                    'content_type' => $this->content()?->getMorphClass(),
                    'content_id' => $this->content()?->getKey(),
                ]),
            ]);
    }

    /**
     * The user whose action caused the notification to be sent.
     *
     * For example, if Bob reacts to Jane's post, causing a notification to be
     * sent to Jane, then Bob is the notification sender.
     */
    public function sender()
    {
        return null;
    }

    /**
     * The model that is the subject of the notification.
     *
     * Notifications of the same type will be grouped by subject so that only
     * one is displayed in the list. For example, if Bob, Fred, and Maria react
     * to Jane's post, then the post is the subject, and Jane will only see
     * one notification about it ("Fred and 2 others reacted to your post").
     */
    public function subject()
    {
        return null;
    }

    /**
     * The model associated with the individual notification instance.
     *
     * For example, if Bob reacts to Jane's post, then the reaction is the
     * content of the notification.
     */
    public function content()
    {
        return null;
    }

    public function groupedUrl(): string
    {
        return $this->url();
    }

    public function unsubscribe(User $user)
    {
        $type = get_class($this);

        if ($channels = $user->notification_channels[$type] ?? null) {
            $user->notification_channels[$type] = $channels[$type]->reject('mail');
            $user->save();
        }
    }
}
