<?php

namespace Waterhole\Console\Concerns;

use Illuminate\Support\Facades\Validator;

trait ValidatesInput
{
    private function askValid(string $question, string $field, array $rules, bool $secret = false)
    {
        $value = $secret ? $this->secret($question) : $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules, $secret);
        }

        return $value;
    }

    private function validateInput(array $rules, string $field, ?string $value): ?string
    {
        $validator = Validator::make([$field => $value], [$field => $rules]);

        return $validator->fails() ? $validator->errors()->first($field) : null;
    }
}
