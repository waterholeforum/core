<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;

class Follow extends Action
{
    public function appliesTo(Model $model): bool
    {
        return method_exists($model, 'follow');
    }

    public function shouldRender(Collection $models, string $context = null): bool
    {
        return $context !== 'cp' && !$models->some->isFollowed();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.follow-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-bell';
    }

    public function run(Collection $models)
    {
        $models->each->follow();
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new FollowButton($model))];
    }
}
