<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Model;

class ReactionsCondensed extends Component
{
    public function __construct(public Model $model)
    {
    }

    public function shouldRender()
    {
        return (bool) $this->model->score;
    }

    public function render()
    {
        return view('waterhole::components.reactions-condensed');
    }
}
