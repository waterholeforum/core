<?php

namespace Waterhole\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Waterhole\Models\User;

class VerifyEmail extends Notification
{
    public function __construct(private User $user, private string $email)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $verificationUrl = $this->verificationUrl();

        return (new MailMessage())
            ->subject(__('waterhole::auth.email-verification-mail-subject'))
            ->line(
                __('waterhole::auth.email-verification-mail-body', [
                    'forum' => config('waterhole.forum.name'),
                ]),
            )
            ->action(__('waterhole::auth.email-verification-mail-button'), $verificationUrl);
    }

    protected function verificationUrl(): string
    {
        return URL::temporarySignedRoute(
            'waterhole.verify-email',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'email' => $this->email,
            ],
        );
    }
}
