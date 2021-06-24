<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Traits\Macroable;

abstract class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable, Macroable;

    public function via($notifiable): array
    {
        return array_merge(['database'], $notifiable->notification_channels[get_class($this)] ?? []);
    }

    public function toArray($notifiable)
    {
        return [
            'sender' => $this->sender($notifiable),
            'subject' => $this->subject($notifiable),
            'content' => $this->content($notifiable),
        ];
    }

    public function toMail($notifiable)
    {
        if ($this instanceof Mailable) {
            $html = Markdown::parse($this->mailText($notifiable));

            return (new MailMessage)
                ->subject(strip_tags($html))
                ->markdown('mail.notification', [
                    'avatar' => $this->sender($notifiable)->avatar,
                    'html' => $html,
                    'actionText' => $this->mailActionText($notifiable),
                    'actionUrl' => $this->mailActionUrl($notifiable),
                    'excerpt' => $this->mailExcerpt($notifiable),
                    'reason' => $this->mailReason($notifiable),
                    'unsubscribeText' => $this->mailUnsubscribeText($notifiable),
                    'unsubscribeUrl' => $this->mailUnsubscribeUrl($notifiable),
                    'notificationSettingsUrl' => route('settings.notifications')
                ]);
        }

        return null;
    }

    /**
     * The user whose action caused the notification to be sent.
     *
     * For example, if Bob reacts to Jane's post, causing a notification to be
     * sent to Jane, then Bob is the notification sender.
     */
    abstract public function sender($notifiable);

    /**
     * The model that is the overarching subject of the notification.
     *
     * Notifications of the same type will be grouped by subject so that only
     * one is displayed in the list. For example, if Bob, Fred, and Maria react
     * to Jane's post, then the post is the subject, and Jane will only see
     * one notification about it ("Fred and 2 others reacted to your post").
     */
    abstract public function subject($notifiable);

    /**
     * The model associated with the individual notification instance.
     *
     * For example, if Bob reacts to Jane's post, then the reaction is the
     * content of the notification.
     */
    abstract public function content($notifiable);
}
