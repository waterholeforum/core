<?php

namespace Waterhole\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    public function __construct(public string $token)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);
        $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage())
            ->markdown('waterhole::mail.email')
            ->subject(__('waterhole::auth.reset-password-mail-subject'))
            ->line(
                __('waterhole::auth.reset-password-mail-body', [
                    'forum' => config('waterhole.forum.name'),
                    'minutes' => $minutes,
                ]),
            )
            ->action(__('waterhole::auth.reset-password-mail-button'), $resetUrl);
    }

    protected function resetUrl($notifiable): string
    {
        return url(
            route(
                'waterhole.reset-password',
                [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ],
                false,
            ),
        );
    }
}
