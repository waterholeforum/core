<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;
use Waterhole\Waterhole;

class Ignore extends Action
{
    public function appliesTo($model): bool
    {
        return method_exists($model, 'ignore');
    }

    public function shouldRender(Collection $models): bool
    {
        return !Waterhole::isCpRoute() && !$models->some->isIgnored();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.ignore-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-eye-off';
    }

    public function run(Collection $models)
    {
        $models->each->ignore();
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new FollowButton($model))];
    }
}
