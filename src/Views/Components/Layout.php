<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public ?string $title;
    public array $assets;

    public function __construct(string $title = null, array $assets = [])
    {
        $this->title = $title;
        $this->assets = $assets;
    }

    public function render()
    {
        // If the current request is intended to retrieve the contents of a
        // Turbo Frame, then don't bother rendering the layout chrome.
        if (request()->header('Turbo-Frame')) {
            return '{{ $slot }}';
        }

        return view('waterhole::components.layout');
    }
}
