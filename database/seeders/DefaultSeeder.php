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
        $guest = Group::firstOrCreate([
            'id' => Group::GUEST_ID,
            'name' => __('waterhole::install.group-guest'),
        ]);

        $member = Group::firstOrCreate([
            'id' => Group::MEMBER_ID,
            'name' => __('waterhole::install.group-member'),
        ]);

        $admin = Group::firstOrCreate([
            'id' => Group::ADMIN_ID,
            'name' => __('waterhole::install.group-admin'),
        ]);

        $mod = Group::firstOrCreate([
            'name' => __('waterhole::install.group-moderator'),
            'is_public' => true,
        ]);

        // Community Guide
        $guide = Page::firstOrCreate(
            ['slug' => Str::slug(__('waterhole::install.guide-title'))],
            [
                'icon' => 'emoji:📖',
                'name' => __('waterhole::install.guide-title'),
                'body' => __('waterhole::install.guide-body'),
            ],
        );

        if ($guide->wasRecentlyCreated) {
            $guide
                ->permissions()
                ->save((new Permission(['ability' => 'view']))->recipient()->associate($guest));

            $guide->structure->update(['is_listed' => true]);
        }

        // Channels
        $channels = [
            [
                'name' => __('waterhole::install.announcements-name'),
                'description' => __('waterhole::install.announcements-description'),
                'icon' => 'emoji:📣',
                'default_layout' => 'cards',
            ],
            [
                'name' => __('waterhole::install.introductions-name'),
                'description' => __('waterhole::install.introductions-description'),
                'icon' => 'emoji:👋',
            ],
            [
                'name' => __('waterhole::install.support-name'),
                'description' => __('waterhole::install.support-description'),
                'icon' => 'emoji:❓',
            ],
            [
                'name' => __('waterhole::install.ideas-name'),
                'description' => __('waterhole::install.ideas-description'),
                'icon' => 'emoji:💡',
            ],
            [
                'name' => __('waterhole::install.staff-name'),
                'description' => __('waterhole::install.staff-description'),
                'icon' => 'emoji:🔒',
                'group' => $mod,
            ],
        ];

        foreach ($channels as $data) {
            $data['slug'] = Str::slug($data['name']);

            $channel = Channel::firstOrCreate(
                Arr::only($data, 'slug'),
                Arr::except($data, ['slug', 'group']),
            );

            if ($channel->wasRecentlyCreated) {
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

                $channel->structure->update(['is_listed' => true]);
            }
        }
    }
}
