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
                'url' => 'https://waterhole.dev/docs',
                'icon' => 'tabler-book',
            ],
            'structure' => [
                'url' => route('waterhole.cp.structure'),
                'icon' => 'tabler-layout-list',
            ],
            'groups' => [
                'url' => route('waterhole.cp.groups.index'),
                'icon' => 'tabler-users',
            ],
            'design' => [
                'url' => 'https://waterhole.dev/community',
                'icon' => 'tabler-bulb',
            ],
        ];
    }

    public function render()
    {
        return view('waterhole::widgets.getting-started');
    }
}
