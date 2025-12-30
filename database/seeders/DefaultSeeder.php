<?php

namespace Waterhole\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Waterhole\Filters;
use Waterhole\Layouts;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Page;
use Waterhole\Models\Permission;
use Waterhole\Models\ReactionSet;

/**
 * A seeder that creates default groups, pages, channels, permissions, and
 * reactions upon installation.
 */
class DefaultSeeder extends Seeder
{
    public function run(): void
    {
        $this->callOnce(GroupsSeeder::class);

        $guest = Group::guest();
        $member = Group::member();
        $mod = Group::custom()->firstOrFail();

        // Community Guide
        $guide = Page::firstOrCreate(
            ['slug' => Str::slug(__('waterhole::install.guide-title'))],
            [
                'icon' => 'emoji:📖',
                'name' => __('waterhole::install.guide-title'),
                'body' => __('waterhole::install.guide-body', [
                    'forumName' => config('waterhole.forum.name'),
                ]),
            ],
        );

        if ($guide->wasRecentlyCreated) {
            $guide
                ->permissions()
                ->save((new Permission(['ability' => 'view']))->recipient()->associate($guest));

            $guide->structure->update(['is_listed' => true]);
        }

        // Reactions
        $emoji = ReactionSet::firstOrNew(
            ['name' => __('waterhole::install.reaction-set-emoji')],
            ['is_default_posts' => true, 'is_default_comments' => true],
        );

        if (!$emoji->exists) {
            $emoji->save();
            $emoji->reactionTypes()->createMany([
                [
                    'name' => __('waterhole::install.reaction-type-like'),
                    'icon' => 'emoji:👍️',
                    'score' => 1,
                ],
                [
                    'name' => __('waterhole::install.reaction-type-love'),
                    'icon' => 'emoji:❤️',
                    'score' => 2,
                ],
                [
                    'name' => __('waterhole::install.reaction-type-laugh'),
                    'icon' => 'emoji:😆',
                    'score' => 1,
                ],
                [
                    'name' => __('waterhole::install.reaction-type-wow'),
                    'icon' => 'emoji:😮',
                    'score' => 1,
                ],
                [
                    'name' => __('waterhole::install.reaction-type-sad'),
                    'icon' => 'emoji:😢',
                    'score' => 1,
                ],
                [
                    'name' => __('waterhole::install.reaction-type-angry'),
                    'icon' => 'emoji:😡',
                    'score' => -1,
                ],
            ]);
        }

        $votes = ReactionSet::firstOrNew(['name' => __('waterhole::install.reaction-set-votes')]);

        if (!$votes->exists) {
            $votes->save();
            $votes->reactionTypes()->create([
                'name' => __('waterhole::install.reaction-type-upvote'),
                'icon' => 'emoji:🔺',
                'score' => 1,
            ]);
        }

        // Channels
        $channels = [
            [
                'name' => __('waterhole::install.announcements-name'),
                'description' => __('waterhole::install.announcements-description'),
                'icon' => 'emoji:📣',
                'layout' => Layouts\CardsLayout::class,
                'filters' => [
                    Filters\Newest::class,
                    Filters\Latest::class,
                    Filters\Top::class,
                    Filters\Oldest::class,
                ],
                'group_post' => $mod,
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
                'answerable' => true,
                'show_similar_posts' => true,
            ],
            [
                'name' => __('waterhole::install.ideas-name'),
                'description' => __('waterhole::install.ideas-description'),
                'icon' => 'emoji:💡',
                'posts_reaction_set_id' => $votes->id,
                'show_similar_posts' => true,
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
                Arr::except($data, ['slug', 'group', 'group_post']) + [
                    'layout' => Layouts\ListLayout::class,
                ],
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
                            ->associate($data['group_post'] ?? ($data['group'] ?? $member)),
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
