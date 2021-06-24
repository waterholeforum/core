<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Tobyz\JsonApiModels\Model;

class Avatar extends Component
{
    protected static $colorCache = [];

    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Model $user = null)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.Avatar');
    }

    public function color()
    {
        if (! $this->user) {
            return 'transparent';
        }

        if (! isset(static::$colorCache[$this->user->displayName])) {
            $len = strlen($this->user->displayName);
            $hue = 0;
            for ($i = 0; $i < $len; $i++) {
                $hue += ord($this->user->displayName[$i]);
            }

            static::$colorCache[$this->user->displayName] = 'hsla('.($hue % 360).', 50%, 50%, 0.5)';
        }

        return static::$colorCache[$this->user->displayName];
    }
}
