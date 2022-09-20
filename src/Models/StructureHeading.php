<?php

namespace Waterhole\Models;

use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\ValidatesData;

/**
 * @property int $id
 * @property string $name
 * @property-read string $edit_url
 */
class StructureHeading extends Model
{
    use Structurable;
    use ValidatesData;

    public $timestamps = false;

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.headings.edit', ['heading' => $this]);
    }

    public static function rules(StructureHeading $instance = null): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
