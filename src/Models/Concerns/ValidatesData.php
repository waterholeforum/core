<?php

namespace Waterhole\Models\Concerns;

/**
 * Methods for models that have validation rules.
 */
trait ValidatesData
{
    /**
     * Validate an array of data against the model's validation rules.
     */
    public static function validate(array $data, self $instance = null): array
    {
        $validator = validator(
            $data,
            static::rules($instance),
            static::messages($instance),
            static::customAttributes($instance),
        );

        // TODO: extension point

        return $validator->validate();
    }

    /**
     * The model's validation rules.
     */
    protected static function rules(self $instance = null): array
    {
        return [];
    }

    /**
     * The model's validation messages.
     */
    protected static function messages(self $instance = null): array
    {
        return [];
    }

    /**
     * The model's validation custom attribute translations.
     */
    protected static function customAttributes(self $instance = null): array
    {
        return [];
    }
}
