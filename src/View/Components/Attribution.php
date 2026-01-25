<?php

namespace Waterhole\View\Components;

use DateTimeInterface;
use Illuminate\View\Component;
use Waterhole\Models\User;

class Attribution extends Component
{
    public function __construct(
        public ?User $user,
        public ?DateTimeInterface $date = null,
        public ?string $permalink = null,
        public ?DateTimeInterface $editDate = null,
    ) {}

    public function render()
    {
        return $this->view('waterhole::components.attribution');
    }
}
