<?php

namespace Waterhole\Models\Concerns;

trait ValidatesData
{
    public static function validate(array $data, self $instance = null)
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

    protected static function rules(self $instance = null)
    {
        return [];
    }

    protected static function messages(self $instance = null)
    {
        return [];
    }

    protected static function customAttributes(self $instance = null)
    {
        return [];
    }
}
