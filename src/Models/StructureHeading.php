<?php

namespace Waterhole\Models;

use Waterhole\Models\Concerns\Structurable;

class StructureHeading extends Model
{
    use Structurable;

    public $timestamps = false;

    public function getEditUrlAttribute()
    {
        return route('waterhole.admin.structure.headings.edit', ['heading' => $this]);
    }

    public static function rules(StructureHeading $group = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
