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
            <x-waterhole::cp.permission-grid
                :abilities="$model->abilities()"
                :scope="$model->exists ? $model : null"
                :defaults="$model->defaultAbilities()"
            />
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['permissions' => ['array']]);
    }

    public function saved(FormRequest $request): void
    {
        $this->model->savePermissions($request->validated('permissions'));
    }
}
