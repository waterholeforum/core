<?php

namespace Waterhole\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Actions\React;

class ActionForm extends Component
{
    public bool $isAuthorized = false;

    public function __construct(
        public $for,
        public ?string $action = null,
        public ?string $return = null,
    ) {
        if ($user = Auth::user()) {
            $this->isAuthorized = resolve(React::class)->authorize($user, $for);
        }
    }

    public function render()
    {
        return $this->view('waterhole::components.action-form');
    }
}
