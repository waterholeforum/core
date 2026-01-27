<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('gate', function () {
    test('comment edit time limit applies to authors', function () {
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

        $comment = Comment::factory()
            ->for($post)
            ->for($user)
            ->create([
                'created_at' => now()->subMinutes(10),
            ]);

        $recentComment = Comment::factory()
            ->for($post)
            ->for($user)
            ->create([
                'created_at' => now()->subMinutes(3),
            ]);

        expect(Gate::forUser($user)->allows('waterhole.comment.edit', $comment))->toBeFalse();
        expect(Gate::forUser($user)->allows('waterhole.comment.edit', $recentComment))->toBeTrue();
    });
});

describe('api', function () {
    test('comments on posts in private channels are not visible', function () {
        $channel = Channel::factory()->create();
        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($post)->create();

        jsonApi('GET', "/api/comments/$comment->id")->assertNotFound();
    });
});
