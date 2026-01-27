<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Providers\RouteServiceProvider;
use Waterhole\Providers\SearchServiceProvider;

uses(RefreshDatabase::class);

describe('Search engine configuration', function () {
    beforeEach(function () {
        $this->seed(GroupsSeeder::class);
    });

    test('full-text search route returns matches', function () {
        $driver = DB::connection()->getDriverName();

        if (!in_array($driver, ['mysql', 'mariadb', 'pgsql'], true)) {
            $this->markTestSkipped('Full-text search requires MySQL/MariaDB/PostgreSQL.');
        }

        Post::factory()
            ->for(Channel::factory()->public()->create())
            ->create([
                'title' => 'Waterhole search term',
                'body' => 'Other content',
            ]);

        $this->get('/search?q=waterhole')->assertOk()->assertSeeText('Waterhole');
    });

    test('disables search routes and header when engine is null', function () {
        config()->set('waterhole.system.search_engine', null);

        app()->register(SearchServiceProvider::class, true);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        Channel::factory()->public()->create();

        $this->get('/')
            ->assertOk()
            ->assertDontSeeHtml('role="search"')
            ->assertDontSee('header-search__button');

        $this->get('/search')->assertNotFound();
    });
});
