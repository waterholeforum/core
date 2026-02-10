<?php

namespace Waterhole\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Waterhole\Models\Model;
use Waterhole\Models\User;

class Report extends Action
{
    public bool $confirm = true;

    public function appliesTo($model): bool
    {
        if (!method_exists($model, 'flags')) {
            return false;
        }

        return !method_exists($model, 'trashed') || !$model->trashed();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && !$user->requiresApproval() && $user->id !== $model->user_id;
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.report-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-flag';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::moderation.report', [
            'reasons' => config('waterhole.forum.report_reasons'),
            'title' => $this->confirmButton($models),
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::forum.report-confirm-button');
    }

    public function run(Collection $models)
    {
        $actor = request()->user();

        $data = request()->validate([
            'reason' => ['required', 'string', Rule::in(config('waterhole.forum.report_reasons'))],
            'note' => ['nullable', 'string'],
        ]);

        $models->each(function (Model $model) use ($actor, $data) {
            $flag = $model->flags()->create([
                'reason' => $data['reason'],
                'note' => $data['note'] ?? null,
                'created_by' => $actor?->getKey(),
            ]);

            $model->pendingFlags->push($flag);
        });
    }
}
