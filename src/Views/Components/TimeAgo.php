<?php

namespace Waterhole\Views\Components;

use Carbon\Carbon;
use DateTime;
use Illuminate\View\Component;

class TimeAgo extends Component
{
    public Carbon $datetime;

    public function __construct(DateTime $datetime)
    {
        $this->datetime = new Carbon($datetime);
    }

    public function render()
    {
        return view('waterhole::components.time-ago');
    }
}
