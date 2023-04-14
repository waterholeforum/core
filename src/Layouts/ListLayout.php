<?php

namespace Waterhole\Layouts;

use Waterhole\Forms\Fields\ChannelLayoutList;

class ListLayout extends Layout
{
    public function label(): string
    {
        return __('waterhole::system.layout-list');
    }

    public function icon(): string
    {
        return 'tabler-list';
    }

    public function wrapperClass(): string
    {
        return 'post-list card';
    }

    public function itemComponent(): string
    {
        return 'waterhole::post-list-item';
    }

    public function configField(): string
    {
        return ChannelLayoutList::class;
    }
}
