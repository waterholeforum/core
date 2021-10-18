<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class Reactions extends Component
{
    public Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function render()
    {
        return view('waterhole::components.reactions');
    }
}
