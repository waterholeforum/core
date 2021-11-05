<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class ReactButton extends Component
{
    public Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function render()
    {
        return view('waterhole::components.react-button');
    }
}
