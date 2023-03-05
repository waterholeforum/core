<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Notification;

class NotificationAlert extends Component
{
    public function __construct(public Notification $notification)
    {
    }

    public function render()
    {
        return <<<'blade'
            <x-waterhole::alert class="alert--notification" dismissible data-key="notification">
                <turbo-frame id="@domid($notification)" src="{{ $notification->url }}">
                    <div class="spinner spinner--sm"></div>
                </turbo-frame>
            </x-waterhole::alert>
        blade;
    }
}
