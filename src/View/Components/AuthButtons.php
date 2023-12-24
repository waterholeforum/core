<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Auth\Providers;

class AuthButtons extends Component
{
    public function __construct(public Providers $providers)
    {
    }

    public function shouldRender(): bool
    {
        return (bool) $this->providers->all();
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="stack gap-sm">
                @foreach ($providers->all() as $provider => $config)
                    <a
                        href="{{ route('waterhole.sso.login', compact('provider')) }}"
                        class="btn auth-button"
                        data-provider="{{ $provider }}"
                    >
                        @icon($config['icon'])
                        {{ __('waterhole::auth.continue-with-provider', ['provider' => $config['name']]) }}
                    </a>
                @endforeach
            </div>
        blade;
    }
}
