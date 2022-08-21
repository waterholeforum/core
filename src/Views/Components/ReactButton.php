<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class ReactButton extends Component
{
    public function __construct(public Model $model)
    {
    }

    public function render()
    {
        return view('waterhole::components.react-button');
    }
}
