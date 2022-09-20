<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Model;
use Waterhole\Views\Components\FollowButton;
use Waterhole\Views\TurboStream;
use Waterhole\Waterhole;

class Unignore extends Action
{
    public function appliesTo(Model $model): bool
    {
        return method_exists($model, 'unignore');
    }

    public function shouldRender(Collection $models): bool
    {
        return !Waterhole::isAdminRoute() && $models->some->isIgnored();
    }

    public function label(Collection $models): string
    {
        return 'Unignore';
    }

    public function icon(Collection $models): string
    {
        return 'tabler-circle-x';
    }

    public function run(Collection $models)
    {
        $models->each->unignore();
    }

    public function stream(Model $model): array
    {
        return [...parent::stream($model), TurboStream::replace(new FollowButton($model))];
    }
}
