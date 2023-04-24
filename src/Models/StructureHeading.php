<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.structure.headings.edit', ['heading' => $this]),
        )->shouldCache();
    }

    public static function rules(StructureHeading $instance = null): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
