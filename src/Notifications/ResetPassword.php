<?php

namespace Waterhole\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage())
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line('If you did not request a password reset, no further action is required.');
    }

    protected function resetUrl($notifiable)
    {
        return url(route('waterhole.reset-password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
