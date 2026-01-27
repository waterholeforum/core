<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('gate', function () {
    test('post edit time limit applies to authors', function () {
        config(['waterhole.forum.edit_time_limit' => 5]);

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
        config(['waterhole.forum.edit_time_limit' => 0]);

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
});

describe('api', function () {
    test('posts in private channels are not visible', function () {
        $channel = Channel::factory()->create();
        $post = Post::factory()->for($channel)->create();

        jsonApi('GET', "/api/posts/$post->id")->assertNotFound();
    });
});

describe('forum', function () {
    test('posts in private channels are not visible', function () {
        $channel = Channel::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->get(route('waterhole.posts.show', ['post' => $post]))->assertNotFound();
    });
});
