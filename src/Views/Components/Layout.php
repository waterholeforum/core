<?php

namespace Waterhole\Views\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Layout extends Component
{
    public array $payload;

    public function __construct(public ?string $title = null, public array $assets = [])
    {
        $this->payload = [
            'userId' => Auth::id(),
            'echoConfig' => config('waterhole.system.echo_config'),
        ];
    }

    public function render()
    {
        return view('waterhole::components.layout');
    }
}
