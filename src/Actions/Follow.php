<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\View\Components\FollowButton;
use Waterhole\View\TurboStream;

class Follow extends Action
{
    public function shouldRender(Collection $models, ?string $context = null): bool
    {
        return $context !== 'cp';
    }

    public function label(Collection $models): string
    {
        return $models[0]->isFollowed()
            ? __('waterhole::forum.unfollow-button')
            : __('waterhole::forum.follow-button');
    }

    public function icon(Collection $models): string
    {
        return $models[0]->isFollowed() ? 'tabler-bell-off' : 'tabler-bell';
    }

    public function run(Collection $models)
    {
        if ($models[0]->isFollowed()) {
            $models->each->unfollow();
        } else {
            $models->each->follow();
        }
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new FollowButton($model))];
    }
}
