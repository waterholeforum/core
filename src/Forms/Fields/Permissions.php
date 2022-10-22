<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Model;

class Permissions extends Field
{
    public function __construct(public ?Model $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::admin.permission-grid
                :abilities="$model->abilities()"
                :permissions="$model->exists ? $model->permissions : null"
                :defaults="$model->defaultAbilities()"
            />
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['permissions' => ['array']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->savePermissions($request->validated('permissions'));
    }
}
