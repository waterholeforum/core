<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Extend\Ui\KeyboardShortcuts;

class Html extends Component
{
    private const MESSAGE_KEYS = ['waterhole::system.unsaved-changes-confirm-message'];

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
            'messages' => collect(self::MESSAGE_KEYS)
                ->mapWithKeys(fn(string $key) => [$key => __($key)])
                ->all(),
            'shortcuts' => collect(resolve(KeyboardShortcuts::class)->shortcuts())
                ->map(fn($shortcut) => $shortcut->toPayload())
                ->values()
                ->all(),
        ];
    }

    public function render()
    {
        return $this->view('waterhole::components.html');
    }
}
