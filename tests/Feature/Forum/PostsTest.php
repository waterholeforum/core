<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Waterhole\Actions\Follow;
use Waterhole\Actions\Ignore;
use Waterhole\Actions\Lock;
use Waterhole\Actions\MoveToChannel;
use Waterhole\Actions\Pin;
use Waterhole\Actions\TrashPost;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\PostDraft;
use Waterhole\Models\User;
use Waterhole\Providers\RouteServiceProvider;
use Waterhole\Providers\SearchServiceProvider;
use Waterhole\Search\LikeSearchEngine;

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

    test('shows similar posts with excerpt while creating a post', function () {
        config()->set('waterhole.system.search_engine', LikeSearchEngine::class);
        app()->register(SearchServiceProvider::class, true);
        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $channel = Channel::factory()
            ->public()
            ->create(['show_similar_posts' => true]);
        $title = 'How do I test similar posts while creating?';

        Post::factory()
            ->for($channel)
            ->create([
                'title' => $title,
                'body' => 'This body excerpt should appear in the similar posts list.',
            ]);

        $this->actingAs(User::factory()->create())
            ->followingRedirects()
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channel->id,
                'title' => $title,
                'body' => 'Draft content',
            ])
            ->assertOk()
            ->assertSeeText($title)
            ->assertSeeText('This body excerpt should appear in the similar posts list.');
    });
});

describe('post drafts', function () {
    test('saves post draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->post(route('waterhole.draft'), [
                'channel_id' => $channel->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ])
            ->assertRedirect(route('waterhole.posts.create', ['channel_id' => $channel->id]));

        $draft = PostDraft::query()->where('user_id', $user->id)->first();

        expect($draft)->not->toBeNull();
        expect($draft->payload['title'])->toBe('Draft title');
        expect($draft->payload['body'])->toBe('Draft body');
    });

    test('does not save empty post draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        PostDraft::create([
            'user_id' => $user->id,
            'payload' => [
                'channel_id' => $channel->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ],
        ]);

        $this->actingAs($user)
            ->from(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->post(route('waterhole.draft'), [
                'channel_id' => $channel->id,
                'title' => '',
                'body' => '',
            ])
            ->assertRedirect(route('waterhole.posts.create', ['channel_id' => $channel->id]));

        $this->assertDatabaseMissing('post_drafts', ['user_id' => $user->id]);
    });

    test('discarding post draft redirects home and clears draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        PostDraft::create([
            'user_id' => $user->id,
            'payload' => [
                'channel_id' => $channel->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ],
        ]);

        $this->actingAs($user)
            ->delete(route('waterhole.draft'))
            ->assertRedirect(route('waterhole.home'));

        $this->assertDatabaseMissing('post_drafts', ['user_id' => $user->id]);
    });

    test('publishing a post clears post draft', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        PostDraft::create([
            'user_id' => $user->id,
            'payload' => [
                'channel_id' => $channel->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ],
        ]);

        $this->actingAs($user)->post(route('waterhole.posts.store'), [
            'channel_id' => $channel->id,
            'title' => 'Published title',
            'body' => 'Published body',
            'commit' => true,
        ]);

        $this->assertDatabaseMissing('post_drafts', ['user_id' => $user->id]);
    });

    test('changing channel without submitting updates post draft payload', function () {
        $channelA = Channel::factory()->public()->create();
        $channelB = Channel::factory()->public()->create();
        $user = User::factory()->create();

        PostDraft::create([
            'user_id' => $user->id,
            'payload' => [
                'channel_id' => $channelA->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ],
        ]);

        $this->actingAs($user)
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channelB->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ])
            ->assertRedirect(route('waterhole.posts.create', ['channel_id' => $channelB->id]));

        $draft = PostDraft::query()->where('user_id', $user->id)->firstOrFail();

        expect($draft->payload['channel_id'])->toBe($channelB->id);
    });

    test('changing channel without title or body clears post draft', function () {
        $channelA = Channel::factory()->public()->create();
        $channelB = Channel::factory()->public()->create();
        $user = User::factory()->create();

        PostDraft::create([
            'user_id' => $user->id,
            'payload' => [
                'channel_id' => $channelA->id,
                'title' => 'Draft title',
                'body' => 'Draft body',
            ],
        ]);

        $this->actingAs($user)
            ->post(route('waterhole.posts.store'), [
                'channel_id' => $channelB->id,
                'title' => '',
                'body' => '',
            ])
            ->assertRedirect(route('waterhole.posts.create', ['channel_id' => $channelB->id]));

        $this->assertDatabaseMissing('post_drafts', ['user_id' => $user->id]);
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
                'action_class' => Pin::class,
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

describe('follow and ignore post', function () {
    test('user can follow and unfollow post with same action', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Follow::class,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'notifications' => 'follow',
        ]);

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Follow::class,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'notifications' => 'normal',
        ]);
    });

    test('user can ignore and unignore post with same action', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Ignore::class,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'notifications' => 'ignore',
        ]);

        $this->actingAs($user)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Ignore::class,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('post_user', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'notifications' => 'normal',
        ]);
    });
});

describe('lock and unlock post', function () {
    test('moderator can lock and unlock post with same action', function () {
        $channel = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();
        $post = Post::factory()->for($channel)->create();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Lock::class,
            ])
            ->assertRedirect();

        expect($post->fresh()->is_locked)->toBeTrue();

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => Post::class,
                'id' => $post->id,
                'action_class' => Lock::class,
            ])
            ->assertRedirect();

        expect($post->fresh()->is_locked)->toBeFalse();
    });
});

describe('feed filters', function () {
    test('following filter shows followed posts', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create();

        $followed = Post::factory()
            ->for($channel)
            ->create(['title' => 'Followed post']);

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Other post']);

        $this->actingAs($user);

        $followed->follow();

        $this->get(route('waterhole.home', ['filter' => 'following']))
            ->assertOk()
            ->assertSeeText('Followed post')
            ->assertDontSeeText('Other post');
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

describe('post heading sidebar', function () {
    test('renders heading tabs for posts with at least two headings', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()
            ->for($channel)
            ->create([
                'body' => "## First Heading\n\nSome text.\n\n### Second Heading\n\nMore text.",
            ]);

        Comment::factory()->for($post)->create();

        $this->get(route('waterhole.posts.show', $post))
            ->assertOk()
            ->assertSee('class="post-headings tabs tabs--vertical gap-xxs hide-md-down"', false)
            ->assertSee('href="#content-first-heading"', false)
            ->assertSee('href="#content-second-heading"', false)
            ->assertDontSee('## First Heading')
            ->assertDontSee('### Second Heading')
            ->assertSee('post-headings__tab--h3', false);
    });

    test('does not render heading tabs for a single heading', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()
            ->for($channel)
            ->create([
                'body' => "## Only Heading\n\nSome text.",
            ]);

        Comment::factory()->for($post)->create();

        $this->get(route('waterhole.posts.show', $post))
            ->assertOk()
            ->assertDontSee(
                'class="post-headings tabs tabs--vertical gap-xxs hide-md-down"',
                false,
            );
    });
});
