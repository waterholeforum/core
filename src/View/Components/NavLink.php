<?php

namespace Waterhole\View\Components;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;

class NavLink extends Component
{
    public bool $isActive;

    public function __construct(
        public string $label,
        public ?string $icon = null,
        public ?string $badge = null,
        public ?string $route = null,
        public ?string $href = null,
        public bool|Closure|null $active = null,
        public ?string $badgeClass = null,
    ) {
        $this->isActive = $this->isActive();
    }

    public function render()
    {
        return $this->view('waterhole::components.nav-link');
    }

    private function isActive(): bool
    {
        if (is_callable($this->active)) {
            return ($this->active)();
        }

        if (isset($this->active)) {
            return $this->active;
        }

        if ($this->route) {
            return Request::routeIs($this->route);
        } elseif ($this->href) {
            return Request::fullUrlIs($this->href . '*');
        }

        return false;
    }
}
