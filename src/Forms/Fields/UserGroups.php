<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Group;
use Waterhole\Models\User;

class UserGroups extends Field
{
    public Collection $groups;

    public function __construct(public ?User $model)
    {
        $this->groups = Group::selectable()->get();
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.user-groups-label') }}
                </div>
                <div class="stack gap-sm">
                    <input type="hidden" name="groups" value="">

                    @foreach ($groups as $group)
                        <label class="choice">
                            <input
                                type="checkbox"
                                name="groups[]"
                                value="{{ $group->id }}"
                                @checked(in_array($group->id, (array) old('groups', isset($model) ? $model->groups->pluck('id')->all() : [])))
                                @disabled($enforce = $group->isAdmin() && $model?->isRootAdmin())
                            >
                            <x-waterhole::group-badge :group="$group"/>
                            @if ($enforce)
                                <input type="hidden" name="groups[]" value="{{ $group->id }}">
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'groups' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if ($this->model->isRootAdmin() && !in_array(Group::ADMIN_ID, $value)) {
                        $fail('Cannot revoke the admin status of a root admin.');
                    }
                },
            ],
            'groups.*' => [
                'integer',
                Rule::exists(Group::class, 'id')->whereNotIn('id', [
                    Group::GUEST_ID,
                    Group::MEMBER_ID,
                ]),
            ],
        ]);
    }

    public function saved(FormRequest $request): void
    {
        if ($request->has('groups')) {
            $this->model->groups()->sync($request->validated('groups'));
        }
    }
}
