<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Waterhole\Database\Seeders\DefaultSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Enums\Mentionable;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Notifications\Mention;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DefaultSeeder::class);
});

test('user mentions notify mentioned users except for author', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->public()->create();
    $author = User::factory()->create(['name' => 'Author']);
    $recipient = User::factory()->create(['name' => 'Mentionable']);

    $post = Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => '@Mentionable @Author',
        ]);

    $this->assertDatabaseHas('mentions', [
        'content_type' => $post->getMorphClass(),
        'content_id' => $post->id,
        'mentionable_type' => $recipient->getMorphClass(),
        'mentionable_id' => $recipient->id,
    ]);

    NotificationFacade::assertSentTo($recipient, Mention::class);
    NotificationFacade::assertNotSentTo($author, Mention::class);
});

test('group mentions notify group members except for author', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->public()->create();
    $group = Group::create([
        'name' => 'Support Team',
        'is_public' => true,
        'mentionable' => Mentionable::Members,
    ]);

    $author = User::factory()->create();
    $recipient = User::factory()->create();

    $author->groups()->attach($group);
    $recipient->groups()->attach($group);

    $post = Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => "@group:Support\xc2\xa0Team",
        ]);

    $this->assertDatabaseHas('mentions', [
        'content_type' => $post->getMorphClass(),
        'content_id' => $post->id,
        'mentionable_type' => $group->getMorphClass(),
        'mentionable_id' => $group->id,
    ]);

    NotificationFacade::assertSentTo($recipient, Mention::class);
    NotificationFacade::assertNotSentTo($author, Mention::class);
});

test('group mentions allow channel moderators even when not members', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->create();
    $moderators = Group::custom()->where('is_public', true)->firstOrFail();

    $channel->savePermissions([
        'group:1' => ['view' => true],
        'group:2' => ['view' => true, 'post' => true, 'comment' => true],
        "group:$moderators->id" => [
            'view' => true,
            'post' => true,
            'comment' => true,
            'moderate' => true,
        ],
    ]);

    $group = Group::create([
        'name' => 'Support Team',
        'is_public' => true,
        'mentionable' => Mentionable::Members,
    ]);

    $author = User::factory()->create();
    $recipient = User::factory()->create();

    $author->groups()->attach($moderators);
    $recipient->groups()->attach($group);

    Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => "@group:Support\xc2\xa0Team",
        ]);

    NotificationFacade::assertSentTo($recipient, Mention::class);
});

test('moderators-only groups cannot be mentioned by non-moderators', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->create();
    $moderators = Group::custom()->where('is_public', true)->firstOrFail();

    $channel->savePermissions([
        'group:1' => ['view' => true],
        'group:2' => ['view' => true, 'post' => true, 'comment' => true],
        'group:' . $moderators->id => [
            'view' => true,
            'post' => true,
            'comment' => true,
            'moderate' => true,
        ],
    ]);

    $group = Group::create([
        'name' => 'Staff',
        'is_public' => true,
        'mentionable' => Mentionable::Moderators,
    ]);

    $author = User::factory()->create();
    $recipient = User::factory()->create();

    $author->groups()->attach($group);
    $recipient->groups()->attach($group);

    Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => '@group:Staff',
        ]);

    NotificationFacade::assertNotSentTo($recipient, Mention::class);
});

test('group mentions do not notify users who cannot view the content', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->create();

    $group = Group::create([
        'name' => 'Foo',
        'is_public' => true,
        'mentionable' => Mentionable::Anyone,
    ]);

    $author = User::factory()->admin()->create();
    $recipient = User::factory()->create();

    $author->groups()->attach($group);
    $recipient->groups()->attach($group);

    Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => '@group:Foo',
        ]);

    NotificationFacade::assertNotSentTo($recipient, Mention::class);
});

test('here mentions notify commenters when used by a moderator', function () {
    NotificationFacade::fake();

    $channel = Channel::factory()->create();
    $moderators = Group::custom()->where('is_public', true)->firstOrFail();

    $channel->savePermissions([
        'group:1' => ['view' => true],
        'group:2' => ['view' => true, 'post' => true, 'comment' => true],
        "group:$moderators->id" => [
            'view' => true,
            'post' => true,
            'comment' => true,
            'moderate' => true,
        ],
    ]);

    $post = Post::factory()
        ->for($channel)
        ->create([
            'user_id' => User::factory()->create()->id,
        ]);

    $commenterA = User::factory()->create();
    $commenterB = User::factory()->create();
    $moderator = User::factory()->create();
    $moderator->groups()->attach($moderators);

    Comment::factory()
        ->for($post)
        ->create([
            'user_id' => $commenterA->id,
            'body' => 'First comment',
        ]);

    Comment::factory()
        ->for($post)
        ->create([
            'user_id' => $commenterB->id,
            'body' => 'Second comment',
        ]);

    Comment::factory()
        ->for($post)
        ->create([
            'user_id' => $moderator->id,
            'body' => '@here',
        ]);

    NotificationFacade::assertSentTo([$commenterA, $commenterB], Mention::class);
    NotificationFacade::assertNotSentTo($moderator, Mention::class);
});

test('group mention is highlighted for members of the mentioned group', function () {
    $channel = Channel::factory()->public()->create();
    $group = Group::create([
        'name' => 'Support Team',
        'is_public' => true,
        'mentionable' => Mentionable::Anyone,
    ]);

    $author = User::factory()->create();
    $member = User::factory()->create();
    $outsider = User::factory()->create();

    $author->groups()->attach($group);
    $member->groups()->attach($group);

    $post = Post::factory()
        ->for($channel)
        ->create([
            'user_id' => $author->id,
            'body' => "@group:Support\xc2\xa0Team",
        ])
        ->load('mentions.mentionable');

    $memberHtml = (string) $post->format('body', $member);
    $outsiderHtml = (string) $post->format('body', $outsider);

    expect($memberHtml)->toContain('mention--group');
    expect($memberHtml)->toContain('mention--self');
    expect($outsiderHtml)->toContain('mention--group');
    expect($outsiderHtml)->not->toContain('mention--self');
});

test('user lookup returns matching groups and users', function () {
    $actor = User::factory()->create();

    User::factory()->create(['name' => 'Lookup User']);
    User::factory()->create(['name' => 'Other User']);

    Group::create([
        'name' => 'Lookup Group',
        'is_public' => true,
        'mentionable' => Mentionable::Anyone,
    ]);

    Group::create([
        'name' => 'Other Group',
        'is_public' => true,
        'mentionable' => Mentionable::Anyone,
    ]);

    $this->actingAs($actor)
        ->getJson(route('waterhole.user-lookup', ['q' => 'Look']))
        ->assertOk()
        ->assertJsonFragment([
            'type' => 'group',
            'name' => 'Lookup Group',
            'value' => 'group:Lookup Group',
        ])
        ->assertJsonFragment([
            'type' => 'user',
            'name' => 'Lookup User',
            'value' => 'Lookup User',
        ])
        ->assertJsonMissing(['name' => 'Other Group'])
        ->assertJsonMissing(['name' => 'Other User']);
});
