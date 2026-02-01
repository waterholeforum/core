<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Waterhole\Models\User;
use Waterhole\Providers\RouteServiceProvider;

uses(RefreshDatabase::class);

describe('api config', function () {
    test('disables api routes when disabled', function () {
        config(['waterhole.api.enabled' => false]);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $this->get('/api/posts')->assertNotFound();
    });

    test('uses configured api path', function () {
        config([
            'waterhole.api.enabled' => true,
            'waterhole.api.path' => 'v2',
        ]);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        jsonApi('GET', '/api/posts')->assertNotFound();
        jsonApi('GET', '/v2/posts')->assertOk();
    });

    test('applies public api middleware settings', function () {
        config([
            'waterhole.api.enabled' => true,
            'waterhole.api.public' => false,
        ]);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        $response = jsonApi('GET', '/api/posts');

        expect($response->getStatusCode())->toBeIn([401, 403]);
    });

    test('supports sanctum token auth', function () {
        config([
            'waterhole.api.enabled' => true,
            'waterhole.api.public' => false,
        ]);

        Route::setRoutes(new RouteCollection());
        app()->register(RouteServiceProvider::class, true);

        Sanctum::actingAs(User::factory()->create(), ['waterhole']);

        jsonApi('GET', '/api/posts')->assertOk();
    });
});
