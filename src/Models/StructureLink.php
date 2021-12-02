<?php

namespace Waterhole\Models;

use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

class StructureLink extends Model
{
    use Structurable;
    use HasPermissions;
    use HasIcon;

    public $timestamps = false;

    public function getEditUrlAttribute()
    {
        return route('waterhole.admin.structure.links.edit', ['link' => $this]);
    }

    public static function rules(StructureLink $link = null): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'href' => ['required', 'string', 'max:255'],
            'permissions' => ['array'],
        ], static::iconRules());
    }
}
