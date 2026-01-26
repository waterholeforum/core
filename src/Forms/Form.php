<?php

namespace Waterhole\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Model;

abstract class Form
{
    private array $fields;

    public function __construct(public Model $model) {}

    abstract public function fields(): array;

    /**
     * Validate the request and save the model.
     */
    public function submit(Request $request): bool
    {
        $formRequest = $this->validate($request);

        return $this->save($formRequest);
    }

    /**
     * Validate the request.
     */
    public function validate(Request $request): FormRequest
    {
        $validator = validator($request->all());

        foreach ($this->getFields() as $field) {
            $field->validating($validator);
        }

        $formRequest = FormRequest::createFrom($request);

        $formRequest->setValidator($validator);

        $validator->validate();

        return $formRequest;
    }

    /**
     * Save the model.
     */
    public function save(FormRequest $request): bool
    {
        return DB::transaction(function () use ($request) {
            foreach ($this->getFields() as $field) {
                $field->saving($request);
            }

            if (!$this->model->save()) {
                return false;
            }

            foreach ($this->getFields() as $field) {
                $field->saved($request);
            }

            return true;
        });
    }

    private function getFields(): array
    {
        return $this->fields ??= array_filter(
            $this->fields(),
            fn($field) => $field?->shouldRender(),
        );
    }
}
