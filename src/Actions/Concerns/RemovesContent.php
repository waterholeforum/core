<?php

namespace Waterhole\Actions\Concerns;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Waterhole\Models\Model;
use Waterhole\Models\User;

trait RemovesContent
{
    use ResolvesFlags;

    public function shouldConfirm(Collection $models): bool
    {
        return request()->user()->id !== $models[0]->user_id;
    }

    public function confirm(Collection $models): View
    {
        $actor = request()->user();
        $model = $models[0];
        $author = $model->user;

        return view('waterhole::moderation.removal-reason', [
            'reasons' => config('waterhole.forum.report_reasons'),
            'model' => $model,
            'canModerate' => $model->canModerate($actor),
            'canSuspend' => $author && ($actor?->can('waterhole.user.suspend', $author) ?? false),
            'title' => $this->confirmButton($models),
        ]);
    }

    public function run(Collection $models)
    {
        $actor = request()->user();

        $data = request()->validate([
            'deleted_reason' => [
                'string',
                'nullable',
                Rule::in(config('waterhole.forum.report_reasons')),
            ],
            'deleted_message' => ['nullable', 'string'],
            'suspend_user' => ['nullable', 'boolean'],
            'suspend_for' => ['nullable', 'integer', 'min:1'],
            'suspend_unit' => ['nullable', 'in:days,weeks,indefinite'],
        ]);

        $models->each(function (Model $model) use ($actor, $data) {
            $isSelf = $actor->id === $model->user_id;

            $model->update([
                'deleted_by' => $actor->id,
                'deleted_reason' => $isSelf ? null : $data['deleted_reason'] ?? null,
                'deleted_message' => $isSelf ? null : $data['deleted_message'] ?? null,
            ]);
            $model->delete();
        });

        $this->suspendAuthorIfRequested($models, $actor, $data);

        return $this->resolveFlags($models);
    }

    protected function suspendAuthorIfRequested(Collection $models, User $actor, array $data): void
    {
        $model = $models->first();

        if (!$model) {
            return;
        }

        $author = $model->user;

        if (!$author || !$models->every(fn(Model $model) => $model->user_id === $author->id)) {
            return;
        }

        if (($data['suspend_user'] ?? false) && $actor->can('waterhole.user.suspend', $author)) {
            $amount = (int) ($data['suspend_for'] ?? 0);

            $suspendedUntil = match ($data['suspend_unit'] ?? null) {
                'indefinite' => '2038-01-01',
                'days' => now()
                    ->addDays($amount)
                    ->toDateTimeString(),
                'weeks' => now()
                    ->addWeeks($amount)
                    ->toDateTimeString(),
                default => null,
            };

            if ($suspendedUntil) {
                $author->update(['suspended_until' => $suspendedUntil]);
            }
        }
    }
}
