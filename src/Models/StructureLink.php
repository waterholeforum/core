<?php

namespace Waterhole\Models;

use Waterhole\Models\Concerns\HasPermissions;
use Waterhole\Models\Concerns\Structurable;

class StructureLink extends Model
{
    use Structurable;
    use HasPermissions;

    public $timestamps = false;
}
