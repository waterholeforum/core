<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;
use Waterhole\Waterhole;

class Unfollow extends Action
{
    public function appliesTo(Model $model): bool
    {
        return method_exists($model, 'unfollow');
    }

    public function shouldRender(Collection $models): bool
    {
        return !Waterhole::isAdminRoute() && $models->some->isFollowed();
    }

    public function label(Collection $models): string
    {
        return 'Unfollow';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-x-circle';
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
