<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Filters\Oldest;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('taxonomy visibility', function () {
    test('private channel not visible to guests', function () {
        $channel = Channel::factory()->create();

        $this->get(route('waterhole.channels.show', $channel))->assertNotFound();
    });

    test('private channel visible to members with permission', function () {
        $channel = Channel::factory()->create();
        $channel->savePermissions([
            'group:2' => ['view' => true],
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)->get(route('waterhole.channels.show', $channel))->assertOk();
    });
});

describe('channel feeds', function () {
    test('channel feed shows posts with permissions applied', function () {
        $channel = Channel::factory()->public()->create();
        $viewer = User::factory()->create();

        Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Visible channel post',
            ]);

        $hidden = Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Hidden channel post',
                'user_id' => User::factory()->create()->id,
            ]);

        $hidden->update(['deleted_by' => $hidden->user_id]);
        $hidden->delete();

        $this->actingAs($viewer)
            ->get(route('waterhole.channels.show', $channel))
            ->assertOk()
            ->assertSeeText('Visible channel post')
            ->assertDontSeeText('Hidden channel post');
    });

    test('channel feed honors configured filters', function () {
        $channel = Channel::factory()->public()->create();
        $channel->update(['filters' => [Oldest::class]]);

        Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Older post',
                'created_at' => now()->subDay(),
                'last_activity_at' => now()->subDay(),
            ]);

        Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Newer post',
                'created_at' => now(),
                'last_activity_at' => now(),
            ]);

        $this->get(route('waterhole.channels.show', $channel))
            ->assertOk()
            ->assertSeeInOrder(['Older post', 'Newer post']);
    });
});
