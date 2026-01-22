<?php

namespace Waterhole\Database\Seeders;

use Illuminate\Database\Seeder;
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

        Group::updateOrCreate([
            'name' => __('waterhole::install.group-moderator'),
            'is_public' => true,
        ]);

        Group::updateOrCreate(
            ['name' => __('waterhole::install.group-quarantine')],
            [
                'is_public' => false,
                'auto_assign' => true,
                'rules' => [
                    'requires_approval' => true,
                    'remove_after_approval' => true,
                ],
            ],
        );
    }
}
