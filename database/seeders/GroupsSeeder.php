<?php

namespace Waterhole\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Waterhole\Models\Group;

/**
 * A seeder that creates default groups.
 */
class GroupsSeeder extends Seeder
{
    public function run(): void
    {
        Group::updateOrCreate([
            'id' => Group::GUEST_ID,
            'name' => __('waterhole::install.group-guest'),
        ]);

        Group::updateOrCreate([
            'id' => Group::MEMBER_ID,
            'name' => __('waterhole::install.group-member'),
        ]);

        Group::updateOrCreate([
            'id' => Group::ADMIN_ID,
            'name' => __('waterhole::install.group-admin'),
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            $model = new Group();
            $table = $model->getConnection()->getTablePrefix() . $model->getTable();

            DB::statement("SELECT setval(pg_get_serial_sequence('\"$table\"', 'id'), (SELECT MAX(id) FROM \"$table\"))");
        }

        Group::updateOrCreate([
            'name' => __('waterhole::install.group-moderator'),
            'is_public' => true,
        ]);
    }
}
