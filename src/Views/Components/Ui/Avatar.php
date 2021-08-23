<?php

namespace Waterhole\Views\Components\Ui;

use Illuminate\View\Component;
use Waterhole\Models\User;

class Avatar extends Component
{
    protected static array $colorCache = [];

    public ?User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('waterhole::components.ui.avatar');
    }

    public function color(): string
    {
        if (! $this->user) {
            return 'transparent';
        }

        if (! isset(static::$colorCache[$this->user->name])) {
            $len = strlen($this->user->name);
            $hue = 0;
            for ($i = 0; $i < $len; $i++) {
                $hue += ord($this->user->name[$i]);
            }

            static::$colorCache[$this->user->name] = 'hsla('.($hue % 360).', 50%, 50%, 0.5)';
        }

        return static::$colorCache[$this->user->name];
    }
}
