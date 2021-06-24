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

use Waterhole\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class ChangeEmail extends BaseNotification
{
    private $user;
    private $email;

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

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('If you do not have an account on '.config('app.name').', no further action is required.'));
    }

    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'verification.change',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'email' => $this->email,
            ]
        );
    }
}
