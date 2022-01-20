<?php

namespace Waterhole\Database\Seeders;

use Illuminate\Database\Seeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Page;
use Waterhole\Models\Permission;

class DefaultSeeder extends Seeder
{
    public function run()
    {
        $guest = Group::create(['id' => Group::GUEST_ID, 'name' => 'Guest']);
        $member = Group::create(['id' => Group::MEMBER_ID, 'name' => 'Member']);
        Group::create(['id' => Group::ADMIN_ID, 'name' => 'Admin']);

        $mod = Group::create([
            'name' => 'Mod',
            'is_public' => true,
        ]);

        $page = Page::create([
            'name' => 'Community Guidelines',
            'slug' => 'community-guidelines',
            'icon' => 'emoji:😇',
            'body' => 'Content',
        ]);

        $page->permissions()->save(
            (new Permission(['ability' => 'view']))->recipient()->associate($guest)
        );

        $channel = Channel::create([
            'name' => 'General',
            'slug' => 'general',
            'icon' => 'emoji:💬',
            'description' => 'A place for general discussion.',
        ]);

        $channel->permissions()->saveMany([
            (new Permission(['ability' => 'view']))->recipient()->associate($guest),
            (new Permission(['ability' => 'post']))->recipient()->associate($member),
            (new Permission(['ability' => 'comment']))->recipient()->associate($member),
            (new Permission(['ability' => 'moderate']))->recipient()->associate($mod),
        ]);
    }
}
