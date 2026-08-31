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
            ->click('.dialog button[type="submit"]');

        visit(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->fill('title', 'Browser smoke post')
            ->fill('body', 'Post body from browser smoke test.')
            ->click('button[name="commit"][value="1"]')
            ->assertSee('Browser smoke post');

        $post = Post::query()->where('title', 'Browser smoke post')->firstOrFail();

        visit($post->url . '#reply')
            ->fill('body', 'Browser smoke comment body.')
            ->click('button[name="commit"][value="1"]')
            ->assertSee('Browser smoke comment body.');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'body' => '<t><p>Browser smoke comment body.</p></t>',
        ]);
    });

    test('uses native same-page fragment navigation', function () {
        $post = Post::factory()->for(Channel::factory()->public())->create();

        $page = visit($post->url);

        $page->script(<<<'JS'
            window.hashChanged = false;
            window.addEventListener('hashchange', () => window.hashChanged = true, { once: true });
            JS);

        $page
            ->click('a[href="#main"]')
            ->assertScript('window.hashChanged')
            ->assertScript('document.activeElement.id', 'main');
    });

    test('navigates to unicode fragments with Turbo', function () {
        $channel = Channel::factory()->public()->create();
        $target = Post::factory()->for($channel)->create([
            'body' => "## 中文設定\n\nTarget body.",
        ]);
        $source = Post::factory()->for($channel)->create([
            'body' => "[Jump to heading]({$target->url}#content-中文設定)",
        ]);

        $page = visit($source->url);

        $page->script(<<<'JS'
            window.turboVisited = false;
            document.addEventListener('turbo:visit', () => window.turboVisited = true, { once: true });
            JS);

        $page
            ->click('Jump to heading')
            ->assertScript('window.turboVisited')
            ->assertScript('document.activeElement.id', 'content-中文設定');
    });

    test('mentions users with multiword names', function () {
        $channel = Channel::factory()->public()->create();
        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'email_verified_at' => now(),
        ]);

        $mentionable = User::factory()->create(['name' => 'Lookup User']);
        User::factory()->create(['name' => 'Lookup Other']);

        visit(route('waterhole.login'))
            ->fill('email', $user->email)
            ->fill('password', 'Password123!')
            ->click('.dialog button[type="submit"]');

        visit(route('waterhole.posts.create', ['channel_id' => $channel->id]))
            ->fill('body', '@Lookup U')
            ->assertSeeIn('[role="listbox"]', 'Lookup User')
            ->assertDontSeeIn('[role="listbox"]', 'Lookup Other')
            ->keys('body', 'Enter')
            ->assertValue('body', "@Lookup\xc2\xa0User ")
            ->click('button[data-text-editor-target="previewButton"]')
            ->assertPresent(
                '.text-editor__preview .mention--user[data-user-id="' . $mentionable->id . '"]',
            );
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
            ->click('.dialog button[type="submit"]');

        visit($post->url)
            ->pressAndWaitFor('button[name="reaction_type_id"][value="' . $reactionType->id . '"]');

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'reaction_type_id' => $reactionType->id,
            'content_type' => $post->getMorphClass(),
            'content_id' => $post->id,
        ]);
    });
});
