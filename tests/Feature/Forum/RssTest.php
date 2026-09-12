<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('RSS feeds', function () {
    test('posts feed includes public posts and excludes private and ignored channels', function () {
        $post = Post::factory()->for(Channel::factory()->public())->create();
        Post::factory()->for(Channel::factory())->create();
        Post::factory()->for(Channel::factory()->public()->state(['ignore' => true]))->create();

        $response = $this->get(route('waterhole.rss.posts'))->assertOk()->assertHeader(
            'Content-Type',
            'application/rss+xml',
        );

        $feed = simplexml_load_string($response->getContent());

        expect((string) $feed->channel->title)
            ->toBe(config('waterhole.forum.name'))
            ->and($feed->channel->item)
            ->toHaveCount(1)
            ->and((string) $feed->channel->item->title)
            ->toBe($post->title)
            ->and((string) $feed->channel->item->link)
            ->toBe($post->url);
    });

    test('channel feed includes only posts from that channel', function () {
        $channel = Channel::factory()->public()->create();
        $post = Post::factory()->for($channel)->create();
        Post::factory()->for(Channel::factory()->public())->create();

        $response = $this->get(route(
            'waterhole.rss.channel',
            compact('channel'),
        ))->assertOk()->assertHeader('Content-Type', 'application/rss+xml');

        $feed = simplexml_load_string($response->getContent());

        expect((string) $feed->channel->title)
            ->toBe($channel->name . ' - ' . config('waterhole.forum.name'))
            ->and($feed->channel->item)
            ->toHaveCount(1)
            ->and((string) $feed->channel->item->link)
            ->toBe($post->url);
    });

    test('private channel feed is not accessible to guests', function () {
        $channel = Channel::factory()->create();

        $this->get(route('waterhole.rss.channel', compact('channel')))->assertNotFound();
    });
});

describe('RSS discovery', function () {
    test('home page advertises the posts feed', function () {
        $this
            ->get(route('waterhole.home'))
            ->assertOk()
            ->assertSeeHtml('type="application/rss+xml"')
            ->assertSeeHtml('href="' . route('waterhole.rss.posts') . '"');
    });

    test('channel page advertises its feed', function () {
        $channel = Channel::factory()->public()->create();

        $this
            ->get($channel->url)
            ->assertOk()
            ->assertSeeHtml('type="application/rss+xml"')
            ->assertSeeHtml('href="' . route('waterhole.rss.channel', compact('channel')) . '"');
    });

    test('page without a feed omits rss discovery', function () {
        $page = Page::factory()->public()->create();

        $this->get($page->url)->assertOk()->assertDontSee('application/rss+xml');
    });
});
