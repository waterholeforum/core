<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Waterhole\Actions\Concerns\ResolvesFlags;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class DismissFlags extends Action
{
    use ResolvesFlags;

    public function authorize(?User $user, Model $model): bool
    {
        return method_exists($model, 'canModerate') &&
            $model->canModerate($user) &&
            $model->pendingFlags->isNotEmpty();
    }

    public function label(Collection $models): string
    {
        if ($this->isApproving($models[0])) {
            return __('waterhole::forum.approve-button');
        }

        return __('waterhole::forum.flag-dismiss-button');
    }

    public function icon(Collection $models): ?string
    {
        if ($this->isApproving($models[0])) {
            return 'tabler-check';
        }

        return 'tabler-flag-off';
    }

    public function run(Collection $models)
    {
        foreach ($models as $model) {
            if ($this->isApproving($model)) {
                $model->update(['is_approved' => true]);
            }
        }

        return $this->resolveFlags($models);
    }

    private function isApproving($model): bool
    {
        return method_exists($model, 'isApproved') && !$model->isApproved();
    }
}
