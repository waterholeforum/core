<?php

namespace Waterhole\Models;

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

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.cp.structure.links.edit', ['link' => $this]);
    }
}
