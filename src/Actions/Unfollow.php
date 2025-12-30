<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;

class Unfollow extends Action
{
    public function shouldRender(Collection $models, string $context = null): bool
    {
        return $context !== 'cp' && $models->some->isFollowed();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.unfollow-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-bell-off';
    }

    public function run(Collection $models)
    {
        $models->each->unfollow();
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new FollowButton($model))];
    }
}
