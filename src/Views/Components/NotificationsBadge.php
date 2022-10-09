<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;
use Waterhole\Views\Components\Concerns\Streamable;

class NotificationsBadge extends Component
{
    use Streamable;

    public int $count;

    public function __construct(public User $user)
    {
        $this->count = $user->unread_notification_count;
    }

    public function render()
    {
        return <<<'blade'
            <span
                {{ $attributes->class('badge bg-activity')->merge([
                    'data-notifications-popup-target' => 'badge',
                    'hidden' => !$count
                ]) }}
            >{{ $count }}</span>
        blade;
    }
}
