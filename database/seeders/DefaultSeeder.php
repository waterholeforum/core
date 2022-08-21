<?php

namespace Waterhole\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Page;
use Waterhole\Models\Permission;

/**
 * A seeder that creates default groups, pages, channels, and permissions
 * upon installation.
 */
class DefaultSeeder extends Seeder
{
    public function run()
    {
        // Groups
        $guest = Group::create([
            'id' => Group::GUEST_ID,
            'name' => __('waterhole::seeder.group-guest'),
        ]);

        $member = Group::create([
            'id' => Group::MEMBER_ID,
            'name' => __('waterhole::seeder.group-member'),
        ]);

        $admin = Group::create([
            'id' => Group::ADMIN_ID,
            'name' => __('waterhole::seeder.group-admin'),
        ]);

        $mod = Group::create([
            'name' => __('waterhole::seeder.group-mod'),
            'is_public' => true,
        ]);

        // Community Guide
        $guide = Page::create([
            'name' => __('waterhole::seeder.guide-title'),
            'slug' => Str::slug(__('waterhole::seeder.guide-title')),
            'icon' => 'emoji:📖',
            'body' => __('waterhole::seeder.guide-body'),
        ]);

        $guide
            ->permissions()
            ->save((new Permission(['ability' => 'view']))->recipient()->associate($guest));

        // Channels
        $channels = [
            [
                'name' => __('waterhole::seeder.announcements-name'),
                'description' => __('waterhole::seeder.announcements-description'),
                'icon' => 'emoji:📣',
            ],
            [
                'name' => __('waterhole::seeder.introductions-name'),
                'description' => __('waterhole::seeder.introductions-description'),
                'icon' => 'emoji:👋',
            ],
            [
                'name' => __('waterhole::seeder.support-name'),
                'description' => __('waterhole::seeder.support-description'),
                'icon' => 'emoji:❓',
            ],
            [
                'name' => __('waterhole::seeder.ideas-name'),
                'description' => __('waterhole::seeder.ideas-description'),
                'icon' => 'emoji:💡',
            ],
            [
                'name' => __('waterhole::seeder.staff-name'),
                'description' => __('waterhole::seeder.staff-description'),
                'icon' => 'emoji:🔒',
                'group' => $mod,
            ],
        ];

        foreach ($channels as $data) {
            $data['slug'] = Str::slug($data['name']);

            $channel = Channel::create(Arr::except($data, 'group'));

            $channel
                ->permissions()
                ->saveMany([
                    (new Permission(['ability' => 'view']))
                        ->recipient()
                        ->associate($data['group'] ?? $guest),
                    (new Permission(['ability' => 'post']))
                        ->recipient()
                        ->associate($data['group'] ?? $member),
                    (new Permission(['ability' => 'comment']))
                        ->recipient()
                        ->associate($data['group'] ?? $member),
                    (new Permission(['ability' => 'moderate']))->recipient()->associate($mod),
                ]);
        }
    }
}
