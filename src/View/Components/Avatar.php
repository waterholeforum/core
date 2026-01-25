<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class Avatar extends Component
{
    protected static array $colorCache = [];

    public function __construct(public ?User $user = null) {}

    public function render()
    {
        return $this->view('waterhole::components.avatar');
    }

    public function color(): string
    {
        if (!$this->user) {
            return 'transparent';
        }

        $name = $this->user->name;

        if (!isset(static::$colorCache[$name])) {
            $len = strlen($name);
            $hue = 0;
            for ($i = 0; $i < $len; $i++) {
                $hue += ord($name[$i]);
            }

            static::$colorCache[$name] = 'hsl(' . $hue % 360 . ' 50% 50% / 0.5)';
        }

        return static::$colorCache[$name];
    }
}
