<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserAvatar extends Field
{
    public function __construct(public User $model) {}

    public function render(): string
    {
        return <<<'blade'
            <div class="field" role="group">
                <div class="field__label">{{ __('waterhole::user.avatar-label') }}</div>
                <div class="row gap-md">
                    <x-waterhole::avatar :user="$model" style="width: 10ch"/>
                    <div class="stack gap-md">
                        <x-waterhole::field name="avatar">
                            <input
                                type="file"
                                name="avatar"
                                accept=".jpg,.jpeg,.png,.bmp,.gif,.webp"
                            >
                        </x-waterhole::field>
                        @if ($model->avatar)
                            <label class="choice">
                                <input type="checkbox" name="remove_avatar" value="1">
                                {{ __('waterhole::user.remove-avatar-label') }}
                            </label>
                        @endif
                    </div>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['avatar' => ['nullable', 'image']]);
    }

    public function saved(FormRequest $request): void
    {
        if ($request->input('remove_avatar')) {
            $this->model->removeAvatar();
        } elseif ($file = $request->file('avatar')) {
            $this->model->uploadAvatar(Image::read($file));
        }
    }
}
