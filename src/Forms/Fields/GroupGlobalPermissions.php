<?php

namespace Waterhole\Forms\Fields;

use Waterhole\Forms\Field;
use Waterhole\Models\Group;

class GroupGlobalPermissions extends Field
{
    public function __construct(public ?Group $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <div role="group" class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.group-global-permissions-title') }}
                </div>
                <div>
                    <input type="hidden" name="permissions[user][suspend]" value="0">
                    <label class="choice">
                        <input
                            type="checkbox"
                            name="permissions[user][suspend]"
                            value="1"
                            @checked(old('permissions.user.suspend', Waterhole::permissions()->can($model, 'suspend', Waterhole\Models\User::class)))
                        >
                        <span>{{ __('waterhole::cp.group-permission-suspend-users-label') }}</span>
                    </label>
                </div>
            </div>
        blade;
    }
}
