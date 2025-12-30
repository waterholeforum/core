<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;

class Ignore extends Action
{
    public function shouldRender(Collection $models, string $context = null): bool
    {
        return $context !== 'cp' && !$models->some->isIgnored();
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
