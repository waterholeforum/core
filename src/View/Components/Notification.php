<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Notification as NotificationModel;

class Notification extends Component
{
    public function __construct(public NotificationModel $notification) {}

    public function shouldRender()
    {
        return $this->notification->template;
    }

    public function render()
    {
        return $this->view('waterhole::components.notification');
    }
}
