<?php

namespace Waterhole\Widgets;

use Illuminate\View\Component;

class GettingStarted extends Component
{
    public array $items;

    public function __construct()
    {
        $this->items = [
            'strategy' => [
                'url' => '#',
                'icon' => 'heroicon-o-map',
            ],
            'structure' => [
                'url' => route('waterhole.admin.structure'),
                'icon' => 'heroicon-o-collection',
            ],
            'groups' => [
                'url' => route('waterhole.admin.groups.index'),
                'icon' => 'heroicon-o-user-group',
            ],
            'design' => [
                'url' => '#',
                'icon' => 'heroicon-o-color-swatch',
            ],
        ];
    }

    public function render()
    {
        return view('waterhole::widgets.getting-started');
    }
}
