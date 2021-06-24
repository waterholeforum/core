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

use DateTime;
use Waterhole\Models\User;

class Suspended extends Notification
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function sender($notifiable)
    {
        return $this->user;
    }

    public function subject($notifiable)
    {
        return $this->user;
    }

    public function content($notifiable)
    {
        return null;
    }

    public function toArray($notifiable)
    {
        return parent::toArray($notifiable) + [
            'suspendUntil' => $this->user->suspend_until
        ];
    }
}
