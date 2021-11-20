<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Group;

class GroupLabel extends Component
{
    public Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function render()
    {
        return view('waterhole::components.group-label');
    }
}
