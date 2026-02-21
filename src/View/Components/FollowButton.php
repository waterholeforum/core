<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use ReflectionClass;
use Waterhole\View\Components\Concerns\Streamable;

class FollowButton extends Component
{
    use Streamable;

    public $followable;
    public string $localePrefix;

    public function __construct($followable)
    {
        $this->followable = $followable;
        $this->localePrefix =
            'waterhole::forum.' . strtolower((new ReflectionClass($followable))->getShortName());
    }

    public function shouldRender(): bool
    {
        return Auth::check();
    }

    public function render()
    {
        return $this->view('waterhole::components.follow-button');
    }
}
