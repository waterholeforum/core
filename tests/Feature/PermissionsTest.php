<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Waterhole;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

test('guests are denied gated abilities when the forum is private', function () {
    config(['waterhole.forum.public' => false]);

    $channel = Channel::factory()->public()->create();

    $user = User::factory()->create();

    expect(Waterhole::permissions()->can(null, 'view', $channel))->toBeFalse();
    expect(Waterhole::permissions()->can($user, 'view', $channel))->toBeTrue();

    expect(Waterhole::permissions()->ids(null, 'view', Channel::class))->toBeEmpty();
    expect(Waterhole::permissions()->ids($user, 'view', Channel::class))->toEqual([$channel->id]);

    expect(Gate::forUser(null)->allows('waterhole.channel.view', $channel))->toBeFalse();
});

test('post edit time limit applies to authors', function () {
    config(['waterhole.forum.post_edit_time_limit' => 5]);

    $user = User::factory()->create();
    $channel = Channel::factory()->public()->create();

    $post = Post::factory()
        ->for($channel)
        ->for($user)
        ->create([
            'created_at' => now()->subMinutes(10),
            'last_activity_at' => now()->subMinutes(10),
        ]);

    $recentPost = Post::factory()
        ->for($channel)
        ->for($user)
        ->create([
            'created_at' => now()->subMinutes(3),
            'last_activity_at' => now()->subMinutes(3),
        ]);

    expect(Gate::forUser($user)->allows('waterhole.post.edit', $post))->toBeFalse();
    expect(Gate::forUser($user)->allows('waterhole.post.edit', $recentPost))->toBeTrue();
});

test('post edit time limit of zero denies editing', function () {
    config(['waterhole.forum.post_edit_time_limit' => 0]);

    $user = User::factory()->create();
    $channel = Channel::factory()->public()->create();

    $post = Post::factory()
        ->for($channel)
        ->for($user)
        ->create([
            'created_at' => now(),
            'last_activity_at' => now(),
        ]);

    expect(Gate::forUser($user)->allows('waterhole.post.edit', $post))->toBeFalse();
});
