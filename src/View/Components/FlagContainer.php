<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Model;

class FlagContainer extends Component
{
    public bool $canModerate;
    public bool $showBanner;

    public function __construct(public Model $subject, public bool $hide = false)
    {
        $this->canModerate = $subject->canModerate(Auth::user());

        $this->showBanner = $this->canModerate
            ? $subject->pendingFlags->isNotEmpty()
            : !$subject->is_approved && !$this->hide;
    }

    public function render()
    {
        return $this->view('waterhole::components.flag-container');
    }
}
