<?php

namespace Waterhole\Views\Components;

use DateTime;
use Illuminate\View\Component;
use Waterhole\Models\User;

class Attribution extends Component
{
    public ?User $user;

    public ?DateTime $date;

    public function __construct(?User $user, DateTime $date = null)
    {
        $this->user = $user;
        $this->date = $date;
    }

    public function render()
    {
        return view('waterhole::components.attribution');
    }
}
