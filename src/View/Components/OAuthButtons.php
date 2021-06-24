<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\OAuth\Providers;

class OAuthButtons extends Component
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
                        href="{{ route('waterhole.oauth.login', compact('provider')) }}"
                        class="btn oauth-button"
                        data-provider="{{ $provider }}"
                    >
                        <x-waterhole::icon :icon="$config['icon']"/>
                        {{ __('waterhole::auth.continue-with-provider', ['provider' => $config['name']]) }}
                    </a>
                @endforeach
            </div>
        blade;
    }
}
