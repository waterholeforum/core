<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\Models\User;

uses(RefreshDatabase::class)->group('browser');

beforeEach(function () {
    $this->seed(GroupsSeeder::class);

    config([
        'filesystems.disks.public.root' => public_path('storage'),
        'filesystems.disks.public.url' => '/storage',
    ]);
});

describe('forum', function () {
    test('creates post and adds comment', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
        ]);

        visit(route('waterhole.login'))
            ->fill('email', $user->email)
            ->fill('password', 'Password123!')
            ->click('button[type="submit"]');

        visit(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->fill('title', 'Browser smoke post')
            ->fill('body', 'Post body from browser smoke test.')
            ->click('Post')
            ->assertSee('Browser smoke post');

        $post = Post::query()->where('title', 'Browser smoke post')->firstOrFail();

        visit($post->url . '#reply')
            ->fill('body', 'Browser smoke comment body.')
            ->click('Post')
            ->assertSee('Browser smoke comment body.');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'body' => '<t><p>Browser smoke comment body.</p></t>',
        ]);
    });

    test('reacts to post', function () {
        $reactionSet = ReactionSet::create([
            'name' => 'Browser Reactions',
            'is_default_posts' => true,
            'is_default_comments' => false,
        ]);

        $reactionType = ReactionType::create([
            'reaction_set_id' => $reactionSet->id,
            'name' => 'Like',
            'score' => 1,
        ]);

        $channel = Channel::factory()
            ->public()
            ->create([
                'posts_reactions_enabled' => true,
                'posts_reaction_set_id' => $reactionSet->id,
            ]);

        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
        ]);

        $post = Post::factory()->create([
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);

        visit(route('waterhole.login'))
            ->fill('email', $user->email)
            ->fill('password', 'Password123!')
            ->click('button[type="submit"]');

        visit($post->url)->pressAndWaitFor(
            'button[name="reaction_type_id"][value="' . $reactionType->id . '"]',
        );

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'reaction_type_id' => $reactionType->id,
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
        ]);
    });
});
