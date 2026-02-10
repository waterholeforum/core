<?php

namespace Waterhole\Actions\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Waterhole\Models\Flag;
use Waterhole\Models\Model;
use Waterhole\View\TurboStream;

trait ResolvesFlags
{
    protected bool $resolvedFlags = false;

    protected function resolveFlags(Collection $models): RedirectResponse|null
    {
        $user = request()->user();

        foreach ($models as $model) {
            if (
                method_exists($model, 'canModerate') &&
                $model->canModerate($user) &&
                $model->resolveFlags($user)
            ) {
                $this->resolvedFlags = true;
            }
        }

        if ($this->resolvedFlags) {
            $next = Flag::query()->pending()->with('subject')->oldest()->first();

            if ($next?->subject) {
                return redirect($next->subject->flagUrl());
            }

            session()->flash('success', __('waterhole::forum.moderation-finished-message'));
        }

        return null;
    }

    public function stream(Model $model): array
    {
        if ($this->resolvedFlags) {
            if (session()->has('success')) {
                return [TurboStream::refresh()];
            }

            return [];
        }

        return parent::stream($model);
    }
}
