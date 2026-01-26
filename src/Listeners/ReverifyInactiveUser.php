<?php

namespace Waterhole\Listeners;

use Illuminate\Auth\Events\Login;
use Waterhole\Models\User;

class ReverifyInactiveUser
{
    public function handle(Login $event): void
    {
        if (!($days = config('waterhole.users.reverify_after_inactive_days'))) {
            return;
        }

        $user = $event->user;

        if (!$user instanceof User || !$user->hasVerifiedEmail()) {
            return;
        }

        $lastSeenAt = $user->last_seen_at ?: $user->created_at;

        if ($lastSeenAt->isAfter(now()->subDays((int) $days))) {
            return;
        }

        $user->update(['email_verified_at' => null]);
        $user->sendEmailVerificationNotification();
    }
}
