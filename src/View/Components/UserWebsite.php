<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class UserWebsite extends Component
{
    public string|null|false $host;

    public function __construct(public User $user)
    {
        $this->host = $user->website ? parse_url($user->website, PHP_URL_HOST) : null;
    }

    public function shouldRender()
    {
        return $this->host;
    }

    public function render()
    {
        return <<<'blade'
            <a
                href="{{ $user->website }}"
                class="with-icon color-muted"
                rel="noopener nofollow ugc"
            >
                @icon('tabler-link')
                <span>{{ $host }}</span>
            </a>
        blade;
    }
}
