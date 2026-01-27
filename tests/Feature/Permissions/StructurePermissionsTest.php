<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Post;
use Waterhole\Models\StructureLink;
use Waterhole\Models\User;
use Waterhole\Waterhole;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('gate', function () {
    test('guests are denied gated abilities without guest permissions', function () {
        $channel = Channel::factory()->create();
        $channel->savePermissions(['group:2' => ['view' => true]]);

        $user = User::factory()->create();

        expect(Waterhole::permissions()->can(null, 'view', $channel))->toBeFalse();
        expect(Waterhole::permissions()->can($user, 'view', $channel))->toBeTrue();

        expect(Waterhole::permissions()->ids(null, 'view', Channel::class))->toBeEmpty();
        expect(Waterhole::permissions()->ids($user, 'view', Channel::class))->toEqual([
            $channel->id,
        ]);

        expect(Gate::forUser(null)->allows('waterhole.channel.view', $channel))->toBeFalse();
    });
});

describe('api', function () {
    test('structure includes only public content', function () {
        $publicChannel = Channel::factory()->public()->create();
        $privateChannel = Channel::factory()->create();

        $publicPage = Page::factory()->public()->create();
        $privatePage = Page::factory()->create();

        $publicLink = StructureLink::create([
            'name' => 'Docs',
            'href' => 'https://example.test/docs',
        ]);
        $publicLink->savePermissions(['group:1' => ['view' => true]]);

        $privateLink = StructureLink::create([
            'name' => 'Private',
            'href' => 'https://example.test/private',
        ]);

        $response = jsonApi('GET', '/api/structure?include=content');

        $response->assertOk();
        $response->assertJsonFragment([
            'type' => 'channels',
            'id' => (string) $publicChannel->id,
        ]);
        $response->assertJsonFragment([
            'type' => 'pages',
            'id' => (string) $publicPage->id,
        ]);
        $response->assertJsonFragment([
            'type' => 'structureLinks',
            'id' => (string) $publicLink->id,
        ]);
        $response->assertJsonMissingExact([
            'type' => 'channels',
            'id' => (string) $privateChannel->id,
        ]);
        $response->assertJsonMissingExact([
            'type' => 'pages',
            'id' => (string) $privatePage->id,
        ]);
        $response->assertJsonMissingExact([
            'type' => 'structureLinks',
            'id' => (string) $privateLink->id,
        ]);
    });

    test('cannot retrieve private channels', function () {
        $channel = Channel::factory()->create();

        jsonApi('GET', "/api/channels/$channel->id")->assertNotFound();
    });

    test('cannot retrieve private pages', function () {
        $page = Page::factory()->create();

        jsonApi('GET', "/api/pages/$page->id")->assertNotFound();
    });

    test('cannot retrieve private structure links', function () {
        $link = StructureLink::create([
            'name' => 'Private',
            'href' => 'https://example.test/private',
        ]);

        jsonApi('GET', "/api/structureLinks/$link->id")->assertNotFound();
    });
});

describe('forum', function () {
    test('private channel is not visible', function () {
        $channel = Channel::factory()->create();

        $this->get(route('waterhole.channels.show', ['channel' => $channel]))->assertNotFound();
    });

    test('home feed hides posts in private channels', function () {
        $public = Channel::factory()->public()->create();
        $private = Channel::factory()->create();

        Post::factory()
            ->for($public)
            ->create([
                'title' => 'Public Post',
                'body' => 'Public body',
            ]);

        Post::factory()
            ->for($private)
            ->create([
                'title' => 'Private Post',
                'body' => 'Private body',
            ]);

        $response = $this->get(route('waterhole.home'));

        $response->assertOk();
        $response->assertSee('Public Post');
        $response->assertDontSee('Private Post');
    });

    test('private pages are not visible', function () {
        $page = Page::factory()->create();

        $this->get(route('waterhole.page', ['page' => $page]))->assertNotFound();
    });
});
