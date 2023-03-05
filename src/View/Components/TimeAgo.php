<?php

namespace Waterhole\View\Components;

use Carbon\Carbon;
use DateTime;
use Illuminate\View\Component;

class TimeAgo extends Component
{
    public ?Carbon $dateTime;

    public function __construct(?DateTime $datetime)
    {
        $this->dateTime = $datetime ? new Carbon($datetime) : null;
    }

    public function shouldRender(): bool
    {
        return (bool) $this->dateTime;
    }

    public function render()
    {
        return view('waterhole::components.time-ago');
    }
}
