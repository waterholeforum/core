<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('Query extenders', function () {
    test('extend post feed query', function () {
        extend(function (Extend\Query\PostFeedQuery $queries) {
            $queries->add(function (Builder $query) {
                $query->where('posts.title', 'Extend Test Feed Scope');
            });
        });

        $channel = Channel::factory()->public()->create();

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Extend Test Feed Scope']);
        Post::factory()
            ->for($channel)
            ->create(['title' => 'Other Post']);

        $this->get(URL::route('waterhole.channels.show', $channel))
            ->assertSeeText('Extend Test Feed Scope')
            ->assertDontSeeText('Other Post');
    });

    test('add post visibility scope', function () {
        extend(function (Extend\Query\PostVisibilityScopes $scopes) {
            $scopes->add(function (Builder $query) {
                $query->where('posts.title', '!=', 'Extend Test Hidden');
            }, 'extend-test');
        });

        $channel = Channel::factory()->public()->create();

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Extend Test Hidden']);
        Post::factory()
            ->for($channel)
            ->create(['title' => 'Visible Post']);

        $this->get(URL::route('waterhole.channels.show', $channel))
            ->assertSeeText('Visible Post')
            ->assertDontSeeText('Extend Test Hidden');
    });
});
