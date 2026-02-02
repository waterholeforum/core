<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Flag;
use Waterhole\Models\Post;
use Waterhole\Models\Reaction;
use Waterhole\Models\ReactionType;
use Waterhole\Models\User;
use Waterhole\Notifications\Mention;
use Waterhole\Notifications\NewComment;
use Waterhole\Notifications\NewFlag;
use Waterhole\Notifications\NewPost;
use Waterhole\Notifications\Reaction as ReactionNotification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DefaultSeeder::class);
});

describe('notification types', function () {
    beforeEach(function () {
        NotificationFacade::fake();
    });

    test('creates notification for new post', function () {
        $channel = Channel::factory()->public()->create();
        $recipient = User::factory()->create();
        $author = User::factory()->create();

        $this->actingAs($recipient);
        $channel->loadUserState($recipient)->follow();

        Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $author->id,
                'title' => 'Followed channel post',
                'is_approved' => true,
            ]);

        NotificationFacade::assertSentTo($recipient, NewPost::class);
    });

    test('creates notification for reply', function () {
        $channel = Channel::factory()->public()->create();
        $recipient = User::factory()->create();
        $author = User::factory()->create();

        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $recipient->id,
                'is_approved' => true,
            ]);

        $this->actingAs($recipient);
        $post->loadUserState($recipient)->follow();

        Comment::factory()
            ->for($post)
            ->create([
                'user_id' => $author->id,
                'body' => 'A reply comment',
                'is_approved' => true,
            ]);

        NotificationFacade::assertSentTo($recipient, NewComment::class);
    });

    test('creates notification for mention', function () {
        $recipient = User::factory()->create();
        $author = User::factory()->create();
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create([
                'user_id' => $author->id,
            ]);

        NotificationFacade::send($recipient, new Mention($post));

        NotificationFacade::assertSentTo($recipient, Mention::class);
    });

    test('creates notification for reaction', function () {
        $channel = Channel::factory()->public()->create();
        $recipient = User::factory()->create();
        $reactor = User::factory()->create();
        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $recipient->id,
            ]);
        $reactionType = ReactionType::query()->firstOrFail();

        Reaction::create([
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
            'user_id' => $reactor->id,
        ]);

        NotificationFacade::assertSentTo($recipient, ReactionNotification::class);
    });

    test('creates notification for flag', function () {
        $channel = Channel::factory()->public()->create();
        $moderator = User::factory()->admin()->create();
        $reporter = User::factory()->create();

        $post = Post::factory()->for($channel)->create();

        Flag::create([
            'subject_type' => $post->getMorphClass(),
            'subject_id' => $post->id,
            'reason' => 'spam',
            'created_by' => $reporter->id,
        ]);

        NotificationFacade::assertSentTo($moderator, NewFlag::class);
    });
});

describe('notifications ui', function () {
    test('marks notification as read', function () {
        $user = User::factory()->create();
        $sender = User::factory()->create();
        $post = Post::factory()
            ->for(Channel::factory()->public())
            ->create([
                'user_id' => $sender->id,
            ]);

        NotificationFacade::send($user, new Mention($post));

        expect($user->unreadNotifications()->count())->toBe(1);

        $this->actingAs($user)
            ->post(route('waterhole.notifications.read'))
            ->assertRedirect(route('waterhole.notifications.index'));

        expect($user->fresh()->unreadNotifications()->count())->toBe(0);
    });
});

describe('notification preferences', function () {
    test('toggles notification preferences', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('waterhole.preferences.notifications'), [
                'follow_on_comment' => false,
                'notification_channels' => [
                    NewPost::class => ['database'],
                    NewComment::class => ['database'],
                    Mention::class => ['database'],
                    ReactionNotification::class => ['database'],
                    NewFlag::class => ['database'],
                ],
            ])
            ->assertRedirect(route('waterhole.preferences.notifications'));

        $user->refresh();

        expect($user->follow_on_comment)->toBeFalse();
        expect($user->notification_channels[NewPost::class])->toBe(['database']);
    });

    test('preference disables notification delivery', function () {
        $channel = Channel::factory()->public()->create();
        $recipient = User::factory()->create();
        $reactor = User::factory()->create();
        $reactionType = ReactionType::query()->firstOrFail();

        $channels = collect($recipient->notification_channels)->toArray();
        $channels[ReactionNotification::class] = [];
        $recipient->update(['notification_channels' => $channels]);

        $post = Post::factory()
            ->for($channel)
            ->create([
                'user_id' => $recipient->id,
            ]);

        Reaction::create([
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
            'reaction_type_id' => $reactionType->id,
            'user_id' => $reactor->id,
        ]);

        $this->assertDatabaseMissing('notifications', [
            'type' => ReactionNotification::class,
            'notifiable_type' => $recipient->getMorphClass(),
            'notifiable_id' => $recipient->id,
        ]);
    });
});
