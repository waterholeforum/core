<?php

namespace Waterhole\View\Components;

use DateTime;
use Illuminate\View\Component;
use Waterhole\Models\User;

class Attribution extends Component
{
    public function __construct(
        public ?User $user,
        public ?DateTime $date = null,
        public ?string $permalink = null,
    ) {
    }

    public function render()
    {
        return $this->view('waterhole::components.attribution');
    }
}
