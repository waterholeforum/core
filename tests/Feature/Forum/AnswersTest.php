<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Filters\Answered;
use Waterhole\Filters\Newest;
use Waterhole\Filters\Oldest;
use Waterhole\Filters\Unanswered;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Models\User;

use function HotwiredLaravel\TurboLaravel\dom_id;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('answer filters', function () {
    test('filter posts by answer state and relevant recency', function () {
        $channel = Channel::factory()
            ->public()
            ->create([
                'answerable' => true,
                'filters' => [Unanswered::class, Answered::class],
            ]);

        $olderUnanswered = Post::factory()->for($channel)->create([
            'title' => 'Older unanswered post',
            'created_at' => now()->subDays(3),
        ]);
        $newerUnanswered = Post::factory()->for($channel)->create([
            'title' => 'Newer unanswered post',
            'created_at' => now()->subDay(),
        ]);
        $olderAnswered = Post::factory()->for($channel)->create([
            'title' => 'Older answered post',
            'created_at' => now()->subDays(2),
        ]);
        $newerAnswered = Post::factory()->for($channel)->create([
            'title' => 'Newer answered post',
            'created_at' => now(),
        ]);

        $recentAnswer = Comment::factory()->for($olderAnswered)->create([
            'created_at' => now(),
        ]);
        $olderAnswered->update(['answer_id' => $recentAnswer->id]);

        $olderAnswer = Comment::factory()->for($newerAnswered)->create([
            'created_at' => now()->subDay(),
        ]);
        $newerAnswered->update(['answer_id' => $olderAnswer->id]);

        $this
            ->get($channel->url . '?filter=unanswered')
            ->assertOk()
            ->assertSeeInOrder([$newerUnanswered->title, $olderUnanswered->title])
            ->assertDontSeeText($newerAnswered->title)
            ->assertDontSeeText($olderAnswered->title);

        $this
            ->get($channel->url . '?filter=answered')
            ->assertOk()
            ->assertSeeInOrder([$olderAnswered->title, $newerAnswered->title])
            ->assertDontSeeText($newerUnanswered->title)
            ->assertDontSeeText($olderUnanswered->title);
    });

    test('require explicit configuration and an answer-enabled channel', function () {
        $channel = Channel::factory()
            ->public()
            ->create([
                'answerable' => true,
            ]);

        $this->get($channel->url . '?filter=answered')->assertNotFound();

        $channel->update([
            'answerable' => false,
            'filters' => [Newest::class, Answered::class],
        ]);

        $this->get($channel->url . '?filter=answered')->assertNotFound();
    });

    test('only include posts from answer-enabled channels globally', function () {
        $user = User::factory()->create();
        $enabled = Channel::factory()->public()->create(['answerable' => true]);
        $disabled = Channel::factory()->public()->create();

        $enabledAnswered = Post::factory()
            ->for($enabled)
            ->for($user)
            ->create([
                'title' => 'Enabled answered post',
            ]);
        $enabledUnanswered = Post::factory()
            ->for($enabled)
            ->for($user)
            ->create([
                'title' => 'Enabled unanswered post',
            ]);
        $disabledAnswered = Post::factory()
            ->for($disabled)
            ->for($user)
            ->create([
                'title' => 'Disabled answered post',
            ]);
        $disabledUnanswered = Post::factory()
            ->for($disabled)
            ->for($user)
            ->create([
                'title' => 'Disabled unanswered post',
            ]);

        foreach ([$enabledAnswered, $disabledAnswered] as $post) {
            $answer = Comment::factory()->for($post)->create();
            $post->update(['answer_id' => $answer->id]);
        }

        config()->set('waterhole.forum.post_filters', [
            Newest::class,
            Answered::class,
            Unanswered::class,
        ]);
        config()->set('waterhole.users.post_filters', [
            Newest::class,
            Answered::class,
            Unanswered::class,
        ]);

        $this
            ->get(route('waterhole.home', ['filter' => 'answered']))
            ->assertOk()
            ->assertSeeText($enabledAnswered->title)
            ->assertDontSeeText($disabledAnswered->title);

        $this
            ->get(route('waterhole.user.posts', [
                $user,
                'filter' => 'unanswered',
            ]))
            ->assertOk()
            ->assertSeeText($enabledUnanswered->title)
            ->assertDontSeeText($disabledUnanswered->title);
    });

    test('use the first available configured filter as the channel default', function () {
        $channel = Channel::factory()
            ->public()
            ->create([
                'filters' => [Answered::class, Oldest::class],
            ]);

        Post::factory()->for($channel)->create([
            'title' => 'Newer post',
            'created_at' => now(),
        ]);
        Post::factory()->for($channel)->create([
            'title' => 'Older post',
            'created_at' => now()->subDay(),
        ]);

        $this->get($channel->url)->assertOk()->assertSeeInOrder(['Older post', 'Newer post']);
    });

    test('fall back to Latest when every configured filter is unavailable', function () {
        $channel = Channel::factory()
            ->public()
            ->create([
                'filters' => [Answered::class, Unanswered::class],
            ]);

        Post::factory()->for($channel)->create([
            'title' => 'Older activity',
            'last_activity_at' => now()->subDay(),
        ]);
        Post::factory()->for($channel)->create([
            'title' => 'Newer activity',
            'last_activity_at' => now(),
        ]);

        $this
            ->get($channel->url)
            ->assertOk()
            ->assertSeeInOrder(['Newer activity', 'Older activity']);

        $this
            ->actingAs(User::factory()->create())
            ->get($channel->url)
            ->assertOk()
            ->assertSeeInOrder(['Newer activity', 'Older activity']);
    });
});

describe('answer navigation', function () {
    test('answered badges target the preview or first answer comment', function () {
        $channel = Channel::factory()->public()->create(['answerable' => true]);

        $previewed = Post::factory()->for($channel)->create();
        Comment::factory()->for($previewed)->create();
        $previewedAnswer = Comment::factory()->for($previewed)->create();
        $previewed->update(['answer_id' => $previewedAnswer->id]);

        $first = Post::factory()->for($channel)->create();
        $firstAnswer = Comment::factory()->for($first)->create();
        $first->update(['answer_id' => $firstAnswer->id]);

        $this
            ->get($channel->url)
            ->assertOk()
            ->assertSee(
                'href="' . $previewed->url . '#' . dom_id($previewed, 'answer') . '"',
                false,
            )
            ->assertSee('href="' . $first->url . '#' . dom_id($first, 'answer') . '"', false);

        $this
            ->get($previewed->url)
            ->assertOk()
            ->assertSeeInOrder([
                'id="' . dom_id($previewed, 'answer') . '"',
                'tabindex="-1"',
            ], false)
            ->assertSee(
                'href="' . $previewed->urlAtIndex(1) . '#' . dom_id($previewedAnswer) . '"',
                false,
            );

        $this->get($first->url)->assertOk()->assertSeeInOrder([
            'id="' . dom_id($first, 'answer') . '"',
            'tabindex="-1"',
        ], false);
    });
});
