<?php

namespace Waterhole\Layouts;

use Illuminate\Database\Eloquent\Builder;
use Waterhole\Forms\Fields\ChannelLayoutCards;

class CardsLayout extends Layout
{
    public function label(): string
    {
        return __('waterhole::system.layout-cards');
    }

    public function icon(): string
    {
        return 'tabler-layout-list';
    }

    public function wrapperClass(): string
    {
        return 'post-cards';
    }

    public function itemComponent(): string
    {
        return 'waterhole::post-card';
    }

    public function scope(Builder $query): void
    {
        $query->with('mentions.mentionable', 'attachments');
    }

    public function configField(): string
    {
        return ChannelLayoutCards::class;
    }
}
