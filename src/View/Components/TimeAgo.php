<?php

namespace Waterhole\View\Components;

use Carbon\Carbon;
use DateTime;
use Illuminate\View\Component;

class TimeAgo extends Component
{
    public Carbon $dateTime;

    public function __construct(DateTime $datetime)
    {
        $this->dateTime = new Carbon($datetime);
    }

    public function render()
    {
        return view('waterhole::components.time-ago');
    }
}
