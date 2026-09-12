<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Group;
use Waterhole\Models\User;
use Waterhole\Providers\RouteServiceProvider;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('Routing extenders', function () {
    test('add and replace api routes', function () {
        app()->forgetInstance(Extend\Routing\ApiRoutes::class);

        extend(function (Extend\Routing\ApiRoutes $routes) {
            Route::get('extend-test', fn() => response()->json(['ok' => true]));
            Route::get('posts', fn() => response()->json(['replaced' => true]));
        });

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $this->get('/api/extend-test')->assertOk()->assertJson(['ok' => true]);
        $this->get('/api/posts')->assertOk()->assertExactJson(['replaced' => true]);
    });

    test('add and replace forum routes', function () {
        app()->forgetInstance(Extend\Routing\ForumRoutes::class);

        extend(function (Extend\Routing\ForumRoutes $routes) {
            Route::get('extend-test', fn() => 'ok');
            Route::get('/', fn() => 'Custom Home')->name('home');
        });

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $this->get('/extend-test')->assertSeeText('ok');
        $this->get(route('waterhole.home'))->assertOk()->assertSeeText('Custom Home');
    });

    test('add and replace cp routes', function () {
        app()->forgetInstance(Extend\Routing\CpRoutes::class);

        extend(function (Extend\Routing\CpRoutes $routes) {
            Route::get('extend-test', fn() => 'ok');
            Route::get('/', fn() => 'Custom Dashboard')->name('dashboard');
        });

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)->get('/cp/extend-test')->assertSeeText('ok');
        $this->get(route('waterhole.cp.dashboard'))->assertOk()->assertSeeText('Custom Dashboard');
    });
});
