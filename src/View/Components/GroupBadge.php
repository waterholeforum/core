<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Group;

class GroupBadge extends Component
{
    public function __construct(public Group $group)
    {
    }

    public function render()
    {
        return view('waterhole::components.group-badge');
    }
}
