<?php

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
    test('add api route', function () {
        $originalRoutes = Route::getRoutes();

        try {
            extend(function (Extend\Routing\ApiRoutes $routes) {
                $routes->register(function () {
                    Route::get('extend-test', fn() => response()->json(['ok' => true]));
                });
            });

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            $this->get('/api/extend-test')->assertOk()->assertJson(['ok' => true]);
        } finally {
            Route::setRoutes($originalRoutes);
        }
    });

    test('add forum route', function () {
        $originalRoutes = Route::getRoutes();

        try {
            extend(function (Extend\Routing\ForumRoutes $routes) {
                $routes->register(function () {
                    Route::get('extend-test', fn() => 'ok');
                });
            });

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            $this->get('/extend-test')->assertSeeText('ok');
        } finally {
            Route::setRoutes($originalRoutes);
        }
    });

    test('add cp route', function () {
        $originalRoutes = Route::getRoutes();

        try {
            extend(function (Extend\Routing\CpRoutes $routes) {
                $routes->register(function () {
                    Route::get('extend-test', fn() => 'ok');
                });
            });

            Route::setRoutes(new RouteCollection());
            app()->register(RouteServiceProvider::class, true);

            $admin = User::factory()->create();
            $admin->groups()->attach(Group::ADMIN_ID);

            $this->actingAs($admin)->get('/cp/extend-test')->assertSeeText('ok');
        } finally {
            Route::setRoutes($originalRoutes);
        }
    });
});
