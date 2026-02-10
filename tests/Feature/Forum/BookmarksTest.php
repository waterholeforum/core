<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\Bookmark as BookmarkAction;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Bookmark;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('bookmarks', function () {
    test('toggles bookmarks for posts and comments', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        $comment = Comment::factory()->for($post)->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => BookmarkAction::class,
        ]);

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
        ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Comment::class,
            'id' => $comment->id,
            'action_class' => BookmarkAction::class,
        ]);

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'content_type' => $comment->getMorphClass(),
            'content_id' => $comment->id,
        ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Post::class,
            'id' => $post->id,
            'action_class' => BookmarkAction::class,
        ]);

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => Comment::class,
            'id' => $comment->id,
            'action_class' => BookmarkAction::class,
        ]);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
        ]);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'content_type' => $comment->getMorphClass(),
            'content_id' => $comment->id,
        ]);
    });

    test('shows saved header icon for authenticated users', function () {
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('waterhole.posts.show', $post))
            ->assertSee('class="menu saved-menu"', false);
    });

    test('hides saved header icon for guests', function () {
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create();

        $this->get(route('waterhole.posts.show', $post))->assertDontSee(
            'class="menu saved-menu"',
            false,
        );
    });

    test('renders saved page with bookmarked content', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Saved Post Title',
                'is_approved' => true,
            ]);
        $comment = Comment::factory()
            ->for($post)
            ->create([
                'body' => 'Saved comment text',
                'is_approved' => true,
            ]);

        $hiddenPost = Post::factory()
            ->for($channel)
            ->create([
                'title' => 'Not My Saved Post',
            ]);

        Bookmark::create([
            'user_id' => $user->id,
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
            'created_at' => now()->subMinute(),
        ]);
        Bookmark::create([
            'user_id' => $user->id,
            'content_type' => $comment->getMorphClass(),
            'content_id' => $comment->id,
        ]);
        Bookmark::create([
            'user_id' => $otherUser->id,
            'content_type' => $hiddenPost->getMorphClass(),
            'content_id' => $hiddenPost->id,
        ]);

        $this->actingAs($user)
            ->get(route('waterhole.saved.index'))
            ->assertOk()
            ->assertSeeText('Saved')
            ->assertSeeText('Saved Post Title')
            ->assertDontSeeText('Not My Saved Post');
    });
});
