<?php

namespace Waterhole\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Model;

abstract class Form
{
    private array $fields;

    public function __construct(protected Model $model)
    {
    }

    abstract public function fields(): array;

    /**
     * Validate the request and save the model.
     */
    public function submit(Request $request): void
    {
        $formRequest = $this->validate($request);

        $this->save($formRequest);
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
    public function save(FormRequest $request): void
    {
        DB::transaction(function () use ($request) {
            foreach ($this->getFields() as $field) {
                $field->saving($request);
            }

            $this->model->save();

            foreach ($this->getFields() as $field) {
                $field->saved($request);
            }
        });
    }

    private function getFields(): array
    {
        return $this->fields ??= array_filter($this->fields());
    }
}
