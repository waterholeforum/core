<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Views\Components\Concerns\Streamable;

class FollowButton extends Component
{
    use Streamable;

    public $followable;

    public string $buttonClass;

    public function __construct($followable, string $buttonClass = 'btn')
    {
        $this->followable = $followable;
        $this->buttonClass = $buttonClass;
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
