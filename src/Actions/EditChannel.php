<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Models\Channel;
use Waterhole\Models\Model;
use Waterhole\Models\User;
use Waterhole\Waterhole;

class EditChannel extends Link
{
    public function appliesTo(Model $model): bool
    {
        return $model instanceof Channel;
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('channel.edit', $model);
    }

    public function shouldRender(Collection $models): bool
    {
        return Waterhole::isCpRoute();
    }

    public function label(Collection $models): string
    {
        return __('waterhole::system.edit-link');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-pencil';
    }

    public function url(Model $model): string
    {
        return $model->edit_url;
    }
}
