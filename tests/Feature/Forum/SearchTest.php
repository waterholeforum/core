<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Providers\RouteServiceProvider;
use Waterhole\Providers\SearchServiceProvider;
use Waterhole\Search\FullTextSearchEngine;
use Waterhole\Search\LikeSearchEngine;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function configureSearchEngine(?string $engine): void
{
    config()->set('waterhole.system.search_engine', $engine);

    app()->register(SearchServiceProvider::class, true);

    Route::setRoutes(new RouteCollection());
    app()->register(RouteServiceProvider::class, true);
}

describe('search interface', function () {
    test('search returns matching visible posts', function () {
        configureSearchEngine(LikeSearchEngine::class);

        $channel = Channel::factory()->public()->create();

        Post::factory()->for($channel)->create(['title' => 'Waterhole search term']);
        Post::factory()->for($channel)->create(['title' => 'Other post']);

        $this
            ->get('/search?q=waterhole')
            ->assertOk()
            ->assertSeeText('Waterhole search term')
            ->assertDontSeeText('Other post');
    });

    test('search sorts results by latest or top', function () {
        configureSearchEngine(LikeSearchEngine::class);

        $channel = Channel::factory()->public()->create();

        Post::factory()->for($channel)->create([
            'title' => 'Top result',
            'body' => 'Search term',
            'score' => 10,
            'created_at' => now()->subDay(),
        ]);
        Post::factory()->for($channel)->create([
            'title' => 'Latest result',
            'body' => 'Search term',
            'score' => 0,
            'created_at' => now(),
        ]);

        $this
            ->get('/search?q=search&sort=latest')
            ->assertOk()
            ->assertSeeInOrder(['Latest result', 'Top result']);

        $this
            ->get('/search?q=search&sort=top')
            ->assertOk()
            ->assertSeeInOrder(['Top result', 'Latest result']);
    });

    test('search excludes posts in private channels', function () {
        configureSearchEngine(LikeSearchEngine::class);

        $publicChannel = Channel::factory()->public()->create();
        $privateChannel = Channel::factory()->create();

        Post::factory()->for($publicChannel)->create(['title' => 'Waterhole search term']);
        Post::factory()->for($privateChannel)->create(['title' => 'Hidden search term']);

        $this
            ->get('/search?q=term')
            ->assertOk()
            ->assertSeeText('Waterhole search term')
            ->assertDontSeeText('Hidden search term');
    });

    test('search respects feed query scopes', function () {
        configureSearchEngine(LikeSearchEngine::class);

        extend(function (Extend\Query\PostFeedQuery $queries) {
            $queries->add(function ($query) {
                $query->where('posts.title', 'Scoped search term');
            });
        });

        $channel = Channel::factory()->public()->create();

        Post::factory()->for($channel)->create(['title' => 'Scoped search term']);
        Post::factory()->for($channel)->create(['title' => 'Other search term']);

        $this
            ->get('/search?q=term')
            ->assertOk()
            ->assertSeeText('Scoped search term')
            ->assertDontSeeText('Other search term');
    });

    test('search disabled hides UI and route returns 404', function () {
        configureSearchEngine(null);

        Channel::factory()->public()->create();

        $this
            ->get('/')
            ->assertOk()
            ->assertDontSeeHtml('role="search"')
            ->assertDontSee('header-search__button');

        $this->get('/search')->assertNotFound();
    });
});

describe('search engines', function () {
    test('full-text search route returns matches when supported', function () {
        if (!in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb', 'pgsql'])) {
            $this->markTestSkipped('Full-text search requires MySQL/MariaDB/PostgreSQL.');
        }

        configureSearchEngine(FullTextSearchEngine::class);

        Post::factory()->for(Channel::factory()->public()->create())->create([
            'title' => 'Waterhole search term',
            'body' => 'Other content',
        ]);

        $this->get('/search?q=waterhole')->assertOk()->assertSeeText('Waterhole');
    });
});
