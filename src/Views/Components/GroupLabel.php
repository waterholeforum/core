<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Group;

class GroupLabel extends Component
{
    public function __construct(public Group $group)
    {
    }

    public function render()
    {
        return view('waterhole::components.group-label');
    }
}
