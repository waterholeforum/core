<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Html extends Component
{
    public array $payload;

    public function __construct(
        public ?string $title = null,
        public ?string $titleSuffix = null,
        public array $assets = [],
        public array $seo = [],
    ) {
        $this->titleSuffix ??= config('waterhole.forum.name');

        $this->payload = [
            'userId' => Auth::id(),
            'debug' => config('app.debug'),
            'echoConfig' => config('waterhole.system.echo_config'),
        ];
    }

    public function render()
    {
        return $this->view('waterhole::components.html');
    }
}
