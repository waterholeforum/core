<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionType;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/posts', function () {
    test('list posts', function () {
        Post::factory(2)
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', '/api/posts?include=channel');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonCount(1, 'included');
    });

    test('retrieve post', function () {
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', "/api/posts/$post->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'posts', 'id' => $post->id]]);
    });

    test('hides deletedBy for posts from author', function () {
        $author = User::factory()->create();
        $moderator = User::factory()->create();
        $channel = Channel::factory()->create();
        $channel->savePermissions([
            'group:1' => ['view' => true],
            "user:{$moderator->id}" => ['moderate' => true],
        ]);

        $post = Post::factory()->for($author)->for($channel)->create();
        $post->update([
            'deleted_by' => $moderator->id,
            'deleted_at' => now(),
        ]);

        $this->actingAs($author);

        $response = jsonApi('GET', "/api/posts/$post->id");

        $response->assertOk();
        $response->assertJsonMissingPath('data.relationships.deletedBy');
    });

    test('shows deletedBy for posts to moderators', function () {
        $moderator = User::factory()->create();
        $channel = Channel::factory()->public()->create();
        $channel->savePermissions([
            'group:1' => ['view' => true],
            "user:{$moderator->id}" => ['moderate' => true],
        ]);

        $post = Post::factory()->for($channel)->create();
        $post->update([
            'deleted_by' => $moderator->id,
            'deleted_at' => now(),
        ]);

        $this->actingAs($moderator);

        $response = jsonApi('GET', "/api/posts/$post->id");

        $response->assertOk();
        $response->assertJsonPath('data.relationships.deletedBy.data.id', (string) $moderator->id);
    });

    test('retrieve post user state', function () {
        $this->actingAs(User::factory()->create());

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $response = jsonApi('GET', "/api/posts/$post->id?include=userState");

        $response->assertOk();
        $response->assertJson([
            'included' => [['type' => 'postUsers', 'attributes' => ['notifications' => null]]],
        ]);
    });

    test('retrieve post tags and taxonomies', function () {
        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);
        $this->actingAs($admin);

        $taxonomy = Taxonomy::create(['name' => 'Topics', 'allow_multiple' => true]);
        $tag = Tag::create(['name' => 'Feature', 'taxonomy_id' => $taxonomy->id]);

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $post->tags()->attach($tag);

        $response = jsonApi('GET', "/api/posts/$post->id?include=tags,tags.taxonomy");

        $response->assertOk();
        $response->assertJsonFragment(['type' => 'tags', 'id' => (string) $tag->id]);
        $response->assertJsonFragment(['type' => 'taxonomies', 'id' => (string) $taxonomy->id]);
    });

    test('retrieve post reactions and reaction counts', function () {
        $this->seed(DefaultSeeder::class);

        $user = User::factory()->create();
        $reactionType = ReactionType::firstOrFail();

        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $reaction = $post->reactions()->create([
            'reaction_type_id' => $reactionType->id,
            'user_id' => $user->id,
        ]);

        $response = jsonApi(
            'GET',
            "/api/posts/$post->id?include=reactions,reactionCounts,reactionCounts.reactionType",
        );

        $response->assertOk();
        $response->assertJsonFragment(['type' => 'reactions', 'id' => (string) $reaction->id]);
        $response->assertJsonFragment(['type' => 'reactionCounts']);
        $response->assertJsonFragment([
            'type' => 'reactionTypes',
            'id' => (string) $reactionType->id,
        ]);
    });
});
