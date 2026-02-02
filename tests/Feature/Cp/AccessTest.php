<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;
use Waterhole\Providers\RouteServiceProvider;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('cp access', function () {
    test('non-admins cannot access cp', function () {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('waterhole.cp.dashboard'))->assertForbidden();
    });

    test('admins can access cp', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('waterhole.cp.dashboard'))->assertOk();
    });
});

describe('cp configuration', function () {
    test('cp routes are configured', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/cp')->assertOk();
    });

    test('cp configuration is respected', function () {
        config(['waterhole.cp.path' => 'admin']);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/cp')->assertNotFound();
        $this->actingAs($admin)->get('/admin')->assertOk();
    });
});
