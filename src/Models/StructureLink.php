<?php

namespace Waterhole\Models;

use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Concerns\ValidatesData;

/**
 * @property int $id
 * @property string $name
 * @property string $href
 * @property-read string $edit_url
 */
class StructureLink extends Model
{
    use HasIcon;
    use HasPermissions;
    use Structurable;
    use ValidatesData;

    public $timestamps = false;

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.structure.links.edit', ['link' => $this]);
    }

    public static function rules(StructureLink $instance = null): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'href' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
