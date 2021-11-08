<?php

namespace Waterhole\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Waterhole\Models\User;

class VerifyEmail extends Notification
{
    private User $user;
    private string $email;

    public function __construct(User $user, string $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail()
    {
        $verificationUrl = $this->verificationUrl();

        return (new MailMessage())
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you do not have an account on '.config('waterhole.forum.title').', no further action is required.');
    }

    protected function verificationUrl()
    {
        return url()->temporarySignedRoute(
            'waterhole.verify-email',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'email' => $this->email,
            ]
        );
    }
}
