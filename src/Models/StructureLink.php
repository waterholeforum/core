<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Waterhole\Models\Concerns\HasIcon;
use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

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

    public $timestamps = false;

    public function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => route('waterhole.cp.structure.links.edit', ['link' => $this]),
        )->shouldCache();
    }
}
