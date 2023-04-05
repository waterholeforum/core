<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\User;

class Avatar extends Component
{
    protected static array $colorCache = [];

    public function __construct(public ?User $user = null)
    {
    }

    public function render()
    {
        return $this->view('waterhole::components.avatar');
    }

    public function color(): string
    {
        if (!$this->user) {
            return 'transparent';
        }

        if (!isset(static::$colorCache[$this->user->name])) {
            $len = strlen($this->user->name);
            $hue = 0;
            for ($i = 0; $i < $len; $i++) {
                $hue += ord($this->user->name[$i]);
            }

            static::$colorCache[$this->user->name] = 'hsl(' . $hue % 360 . ' 50% 50% / 0.5)';
        }

        return static::$colorCache[$this->user->name];
    }
}
