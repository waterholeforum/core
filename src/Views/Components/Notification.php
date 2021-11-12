<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Notification as NotificationModel;

class Notification extends Component
{
    public NotificationModel $notification;

    public function __construct(NotificationModel $notification)
    {
        $this->notification = $notification;
    }

    public function render()
    {
        return view('waterhole::components.notification');
    }
}
