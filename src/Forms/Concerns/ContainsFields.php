<?php

namespace Waterhole\Forms\Concerns;

use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Forms\Form;

use function Waterhole\build_components;

trait ContainsFields
{
    private array $fields;

    abstract private function getComponents(): array;

    public function validating(Validator $validator, Form $form): void
    {
        $this->call('validating', $validator, $form);
    }

    public function saving($model, Form $form): void
    {
        $this->call('saving', $model, $form);
    }

    public function saved($model, Form $form): void
    {
        $this->call('saved', $model, $form);
    }

    private function call(string $method, ...$arguments): void
    {
        foreach ($this->fields ??= build_components($this->getComponents()) as $component) {
            if ($component instanceof Field) {
                $component->$method(...$arguments);
            }
        }
    }
}
