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
                'icon' => 'tabler-map',
            ],
            'structure' => [
                'url' => route('waterhole.admin.structure'),
                'icon' => 'tabler-layout-list',
            ],
            'groups' => [
                'url' => route('waterhole.admin.groups.index'),
                'icon' => 'tabler-users',
            ],
            'design' => [
                'url' => '#',
                'icon' => 'tabler-color-swatch',
            ],
        ];
    }

    public function render()
    {
        return view('waterhole::widgets.getting-started');
    }
}
