<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Intervention\Image\Facades\Image;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserAvatar extends Field
{
    public function __construct(public ?User $user)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field" role="group">
                <div class="field__label">{{ __('waterhole::user.avatar-label') }}</div>
                <div class="row gap-md">
                    <x-waterhole::avatar :user="$user" style="width: 10ch"/>
                    <div class="stack gap-md">
                        <input
                            type="file"
                            class="input"
                            name="avatar"
                            accept="image/*,.jpg,.png,.gif,.bmp"
                            capture="user"
                        >
                        @if ($user?->avatar)
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
            $this->user->removeAvatar();
        }

        if ($file = $request->file('avatar')) {
            $this->user->uploadAvatar(Image::make($file));
        }
    }
}
