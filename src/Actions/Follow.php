<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;
use Waterhole\Waterhole;

class Follow extends Action
{
    public function appliesTo(Model $model): bool
    {
        return method_exists($model, 'follow');
    }

    public function shouldRender(Collection $models): bool
    {
        return ! Waterhole::isAdminRoute() && $models->some(fn ($item) => ! $item->userState->notifications);
    }

    public function label(Collection $models): string
    {
        return 'Follow';
    }

    public function icon(Collection $models): string
    {
        return 'heroicon-o-bell';
    }

    public function run(Collection $models)
    {
        $models->each->follow();
    }

    public function stream(Model $model): array
    {
        return [
            ...parent::stream($model),
            TurboStream::replace(new FollowButton($model)),
        ];
    }
}
