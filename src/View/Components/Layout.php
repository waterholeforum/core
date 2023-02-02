<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Layout extends Component
{
    public array $payload;

    public function __construct(public ?string $title = null, public array $assets = [])
    {
        $this->payload = [
            'userId' => Auth::id(),
            'debug' => config('app.debug'),
            'echoConfig' => config('waterhole.system.echo_config'),
            'twemojiBase' => config('waterhole.design.twemoji_base'),
        ];
    }

    public function render()
    {
        return view('waterhole::components.layout');
    }
}
