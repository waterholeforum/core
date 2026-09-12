<?php

declare(strict_types=1);

namespace Waterhole\Database;

use Illuminate\Database\Migrations\Migration as BaseMigration;

class Migration extends BaseMigration
{
    public function getConnection()
    {
        return config('waterhole.system.database');
    }
}
