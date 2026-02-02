<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/comments', function () {
    test('list comments', function () {
        Post::factory()
            ->for(Channel::factory()->public())
            ->has(Comment::factory(2))
            ->create();

        $response = jsonApi('GET', '/api/comments');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    });

    test('retrieve comment', function () {
        $comment = Comment::factory()
            ->for(Post::factory()->for(Channel::factory()->public()))
            ->create();

        $response = jsonApi('GET', "/api/comments/$comment->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'comments', 'id' => $comment->id]]);
    });

    test('hides deletedBy for comments from author', function () {
        $author = User::factory()->create();
        $moderator = User::factory()->create();
        $channel = Channel::factory()->public()->create();
        $channel->savePermissions([
            'group:1' => ['view' => true],
            "user:{$moderator->id}" => ['moderate' => true],
        ]);

        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($author)->for($post)->create();
        $comment->update([
            'deleted_by' => $moderator->id,
            'deleted_at' => now(),
        ]);

        $this->actingAs($author);

        $response = jsonApi('GET', "/api/comments/$comment->id");

        $response->assertOk();
        $response->assertJsonMissingPath('data.relationships.deletedBy');
    });

    test('shows deletedBy for comments to moderators', function () {
        $moderator = User::factory()->create();
        $channel = Channel::factory()->public()->create();
        $channel->savePermissions([
            'group:1' => ['view' => true],
            "user:{$moderator->id}" => ['moderate' => true],
        ]);

        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($post)->create();
        $comment->update([
            'deleted_by' => $moderator->id,
            'deleted_at' => now(),
        ]);

        $this->actingAs($moderator);

        $response = jsonApi('GET', "/api/comments/$comment->id");

        $response->assertOk();
        $response->assertJsonPath('data.relationships.deletedBy.data.id', (string) $moderator->id);
    });
});
