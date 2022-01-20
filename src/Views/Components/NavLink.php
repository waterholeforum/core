<?php

namespace Waterhole\Views\Components;

use Closure;
use Illuminate\View\Component;

class NavLink extends Component
{
    public string $label;
    public ?string $icon;
    public ?string $badge;
    public ?string $route;
    public ?string $href;
    public bool|Closure|null $active;

    public function __construct(
        string $label,
        string $icon = null,
        string $badge = null,
        string $route = null,
        string $href = null,
        bool|Closure $active = null,
    ) {
        $this->label = $label;
        $this->icon = $icon;
        $this->badge = $badge;
        $this->route = $route;
        $this->href = $href;
        $this->active = $active;
    }

    public function render()
    {
        return view('waterhole::components.nav-link');
    }

    public function isActive(): bool
    {
        if (is_callable($this->active)) {
            return ($this->active)();
        }

        if (isset($this->active)) {
            return $this->active;
        }

        if ($this->route) {
            return request()->routeIs($this->route);
        }

        return false;
    }
}
