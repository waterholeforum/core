<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\MoveToChannel;
use Waterhole\Actions\Pin;
use Waterhole\Actions\TrashPost;
use Waterhole\Actions\Unpin;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('create post', function () {
    test('shows post create form', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->assertOk()
            ->assertSee('name="title"', false)
            ->assertSee('name="body"', false);
    });

    test('creates post with valid input', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('waterhole.posts.store'), [
            'channel_id' => $channel->id,
            'title' => 'New Post Title',
            'body' => 'Post body text.',
            'commit' => true,
        ]);

        $post = Post::where('title', 'New Post Title')->first();

        $response->assertRedirect($post->url);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);
    });

    test('validates required fields', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => '',
                'body' => '',
                'commit' => true,
            ])
            ->assertRedirect(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->assertSessionHasErrors(['title', 'body']);
    });

    test('applies channel permission to create', function () {
        $channel = Channel::factory()->readOnly()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => 'Unauthorized post',
                'body' => 'Should not be created.',
                'commit' => true,
            ])
            ->assertForbidden();
    });
});

describe('edit post', function () {
    test('shows post edit form for author', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('waterhole.posts.edit', $post))
            ->assertOk()
            ->assertSee('value="' . e($post->title) . '"', false);
    });

    test('updates post with valid input', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('waterhole.posts.update', $post), [
                'title' => 'Updated title',
                'body' => 'Updated body',
            ])
            ->assertRedirect($post->fresh()->url);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated title',
        ]);
    });

    test('denies post edit after time limit for non-moderators', function () {
        config(['waterhole.forum.edit_time_limit' => 10]);

        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $user->id,
                'created_at' => now()->subMinutes(20),
            ]);

        $this->actingAs($user)->get(route('waterhole.posts.edit', $post))->assertForbidden();
    });

    test('moderators can edit posts beyond time limit', function () {
        config(['waterhole.forum.edit_time_limit' => 10]);

        $channel = Channel::factory()->public()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'created_at' => now()->subMinutes(20),
            ]);

        $moderator = User::factory()->admin()->create();

        $this->actingAs($moderator)->get(route('waterhole.posts.edit', $post))->assertOk();
    });
});

describe('delete post', function () {
    test('author can soft-delete with permission', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $user->id,
                'comment_count' => 0,
            ]);

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => TrashPost::class,
                'return' => $post->url,
            ])
            ->assertRedirect();

        expect($post->fresh()->trashed())->toBeTrue();
    });

    test('moderator can delete', function () {
        $channel = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();

        $post = Post::factory()
            ->for($channel)
            ->create(['comment_count' => 2]);

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => TrashPost::class,
                'return' => $post->url,
                'confirmed' => true,
            ])
            ->assertRedirect();

        expect($post->fresh()->trashed())->toBeTrue();
    });

    test('deleted post hidden from feeds', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $viewer = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $user->id,
                'comment_count' => 0,
            ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => TrashPost::class,
            'return' => $post->url,
        ]);

        $this->actingAs($viewer)
            ->get(route('waterhole.home'))
            ->assertOk()
            ->assertDontSeeText($post->title);
    });
});

describe('pin and unpin post', function () {
    test('moderator can pin and unpin post', function () {
        $channel = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();

        $post = Post::factory()->for($channel)->create();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Pin::class,
            ])
            ->assertRedirect();

        expect($post->fresh()->is_pinned)->toBeTrue();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Unpin::class,
            ])
            ->assertRedirect();

        expect($post->fresh()->is_pinned)->toBeFalse();
    });

    test('pinned post influences feed ordering', function () {
        $channel = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();

        $pinned = Post::factory()
            ->for($channel)
            ->create(['title' => 'Pinned Post']);
        $other = Post::factory()
            ->for($channel)
            ->create(['title' => 'Other Post']);

        $this->actingAs($moderator)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $pinned->id,
            'action_class' => Pin::class,
        ]);

        $this->get(route('waterhole.home'))
            ->assertOk()
            ->assertSeeInOrder(['Pinned Post', 'Other Post']);
    });
});

describe('move post between channels', function () {
    test('moderator can move post between channels', function () {
        $channelA = Channel::factory()->public()->create();
        $channelB = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();

        $post = Post::factory()->for($channelA)->create();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => MoveToChannel::class,
                'channel_id' => $channelB->id,
                'confirmed' => true,
            ])
            ->assertRedirect();

        expect($post->fresh()->channel_id)->toBe($channelB->id);
    });

    test('post disappears from old channel feed and appears in new', function () {
        $channelA = Channel::factory()->public()->create();
        $channelB = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();

        $post = Post::factory()
            ->for($channelA)
            ->create(['title' => 'Moved Post']);

        $this->actingAs($moderator)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => MoveToChannel::class,
            'channel_id' => $channelB->id,
            'confirmed' => true,
        ]);

        $this->get(route('waterhole.channels.show', $channelA))
            ->assertOk()
            ->assertDontSeeText('Moved Post');

        $this->get(route('waterhole.channels.show', $channelB))
            ->assertOk()
            ->assertSeeText('Moved Post');
    });
});
