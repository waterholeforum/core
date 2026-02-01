<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\React;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionType;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DefaultSeeder::class);
});

describe('react', function () {
    test('adds reaction to a post', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $user = User::factory()->create();
        $reactionType = ReactionType::query()->firstOrFail();

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => React::class,
                'reaction_type_id' => $reactionType->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reactions', [
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
            'user_id' => $user->id,
        ]);
    });

    test('adds reaction to a comment', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($post)->create();
        $user = User::factory()->create();
        $reactionType = ReactionType::query()->firstOrFail();

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Comment::class,
                'id' => $comment->id,
                'action_class' => React::class,
                'reaction_type_id' => $reactionType->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reactions', [
            'content_type' => $comment->getMorphClass(),
            'content_id' => $comment->id,
            'reaction_type_id' => $reactionType->id,
            'user_id' => $user->id,
        ]);
    });

    test('prevents reactions when restricted', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $reactionType = ReactionType::query()->firstOrFail();

        $this->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ])->assertForbidden();
    });

    test('removes reaction', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $user = User::factory()->create();
        $reactionType = ReactionType::query()->firstOrFail();

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $this->assertDatabaseMissing('reactions', [
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
            'user_id' => $user->id,
        ]);
    });
});

describe('reaction counts', function () {
    test('reaction counts for a post are accurate', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $reactionType = ReactionType::query()->firstOrFail();
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $this->actingAs($userB)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $count = $post->reactionCounts()->where('reaction_types.id', $reactionType->id)->first();

        expect($count->count)->toBe(2);
    });

    test('reaction counts for a comment are accurate', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($post)->create();
        $reactionType = ReactionType::query()->firstOrFail();
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA)->post(route('waterhole.actions.store'), [
            'actionable' => Comment::class,
            'id' => $comment->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $this->actingAs($userB)->post(route('waterhole.actions.store'), [
            'actionable' => Comment::class,
            'id' => $comment->id,
            'action_class' => React::class,
            'reaction_type_id' => $reactionType->id,
        ]);

        $count = $comment->reactionCounts()->where('reaction_types.id', $reactionType->id)->first();

        expect($count->count)->toBe(2);
    });
});
