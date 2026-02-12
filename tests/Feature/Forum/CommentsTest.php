<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\RemoveComment;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('create comment', function () {
    test('creates comment with valid input', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $response = $this->actingAs($user)->post(route('waterhole.posts.comments.store', $post), [
            'body' => 'New comment body.',
            'commit' => true,
        ]);

        $comment = Comment::withoutGlobalScope('index')->first();

        $response->assertRedirect($comment->post_url);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    });

    test('validates required fields', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)
            ->from(route('waterhole.posts.comments.create', $post))
            ->post(route('waterhole.posts.comments.store', $post), [
                'body' => '',
                'commit' => true,
            ])
            ->assertRedirect(route('waterhole.posts.comments.create', $post))
            ->assertSessionHasErrors(['body']);
    });

    test('applies channel permission to comment', function () {
        $channel = Channel::factory()->readOnly()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)
            ->post(route('waterhole.posts.comments.store', $post), [
                'body' => 'Not allowed',
                'commit' => true,
            ])
            ->assertForbidden();
    });
});

describe('comment drafts', function () {
    test('saves comment draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)
            ->from($post->url)
            ->post(route('waterhole.posts.draft', $post), [
                'body' => 'Draft comment body',
            ])
            ->assertRedirect($post->url);

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'draft_body' => 'Draft comment body',
        ]);
    });

    test('removing parent saves comment draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();
        $parent = Comment::factory()->for($post)->create();

        $this->actingAs($user)->post(route('waterhole.posts.draft', $post), [
            'body' => 'Draft comment body',
            'parent_id' => $parent->id,
        ]);

        $this->actingAs($user)
            ->from(route('waterhole.posts.comments.create', ['post' => $post, 'parent' => $parent]))
            ->post(route('waterhole.posts.comments.store', $post), [
                'body' => 'Draft comment body',
                'parent_id' => '',
            ])
            ->assertRedirect(route('waterhole.posts.comments.create', $post));

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'draft_body' => 'Draft comment body',
            'draft_parent_id' => null,
        ]);
    });

    test('discarding a comment draft clears it', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)->post(route('waterhole.posts.draft', $post), [
            'body' => 'Draft to discard',
        ]);

        $this->actingAs($user)
            ->delete(route('waterhole.posts.draft', $post))
            ->assertRedirect($post->url);

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'draft_body' => null,
            'draft_parent_id' => null,
            'draft_saved_at' => null,
        ]);
    });

    test('submitting a comment clears comment draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)->post(route('waterhole.posts.draft', $post), [
            'body' => 'Draft to clear',
        ]);

        $this->actingAs($user)->post(route('waterhole.posts.comments.store', $post), [
            'body' => 'Published comment',
            'commit' => true,
        ]);

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'draft_body' => null,
            'draft_parent_id' => null,
            'draft_saved_at' => null,
        ]);
    });
});

describe('edit comment', function () {
    test('author can edit within time limit', function () {
        config(['waterhole.forum.edit_time_limit' => 10]);

        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $comment = Comment::factory()
            ->for($post)
            ->create([
                'user_id' => $user->id,
                'created_at' => now()->subMinutes(5),
            ]);

        $this->actingAs($user)
            ->patch(route('waterhole.posts.comments.update', [$post, $comment]), [
                'body' => 'Updated comment body',
            ])
            ->assertRedirect(Comment::first()->post_url);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => '<t><p>Updated comment body</p></t>',
        ]);
    });

    test('author cannot edit after time limit', function () {
        config(['waterhole.forum.edit_time_limit' => 10]);

        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $comment = Comment::factory()
            ->for($post)
            ->create([
                'user_id' => $user->id,
                'created_at' => now()->subMinutes(20),
            ]);

        $this->actingAs($user)
            ->get(route('waterhole.posts.comments.edit', [$post, $comment]))
            ->assertForbidden();
    });

    test('moderator can edit after time limit', function () {
        config(['waterhole.forum.edit_time_limit' => 10]);

        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();

        $comment = Comment::factory()
            ->for($post)
            ->create([
                'created_at' => now()->subMinutes(20),
            ]);

        $moderator = User::factory()->admin()->create();

        $this->actingAs($moderator)
            ->get(route('waterhole.posts.comments.edit', [$post, $comment]))
            ->assertOk();
    });
});

describe('delete comment', function () {
    test('author can delete with permission', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $comment = Comment::factory()
            ->for($post)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Comment::class,
                'id' => $comment->id,
                'action_class' => RemoveComment::class,
                'return' => $comment->post_url,
            ])
            ->assertRedirect();

        expect($comment->fresh()->trashed())->toBeTrue();
    });

    test('moderator can delete', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $moderator = User::factory()->admin()->create();

        $comment = Comment::factory()->for($post)->create();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Comment::class,
                'id' => $comment->id,
                'action_class' => RemoveComment::class,
                'return' => $comment->post_url,
                'confirmed' => true,
            ])
            ->assertRedirect();

        expect($comment->fresh()->trashed())->toBeTrue();
    });

    test('deleted comment hidden from thread', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $viewer = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $comment = Comment::factory()
            ->for($post)
            ->create([
                'user_id' => $user->id,
                'body' => 'Hidden comment',
            ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Comment::class,
            'id' => $comment->id,
            'action_class' => RemoveComment::class,
            'return' => $comment->post_url,
        ]);

        $this->actingAs($viewer)
            ->get(route('waterhole.posts.show', $post))
            ->assertOk()
            ->assertDontSeeText('Hidden comment');
    });
});
