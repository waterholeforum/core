<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use ReflectionClass;
use Waterhole\Views\Components\Concerns\Streamable;

class FollowButton extends Component
{
    use Streamable;

    public $followable;
    public string $localePrefix;

    public function __construct($followable, public string $buttonClass = 'btn')
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
        return view('waterhole::components.follow-button');
    }
}
