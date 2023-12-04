<?php

namespace Waterhole\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::aliasMiddleware(
            'waterhole.confirm-password',
            \Waterhole\Http\Middleware\MaybeRequirePassword::class,
        );

        Route::middlewareGroup('waterhole.web', [
            \Tonysm\TurboLaravel\Http\Middleware\TurboMiddleware::class,
            \Waterhole\Http\Middleware\ContactOutpost::class,
            \Waterhole\Http\Middleware\ActorSeen::class,
            \Waterhole\Http\Middleware\Localize::class,
            \Waterhole\Http\Middleware\PoweredByHeader::class,
        ]);

        Route::middlewareGroup('waterhole.cp', [
            'auth',
            'can:waterhole.administrate',
            \Waterhole\Http\Middleware\MaybeRequirePassword::class,
        ]);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['web', 'waterhole.web'])
                ->name('waterhole.')
                ->prefix(config('waterhole.forum.path'))
                ->group(__DIR__ . '/../../routes/forum.php');

            Route::middleware(['web', 'waterhole.web', 'waterhole.cp'])
                ->name('waterhole.cp.')
                ->prefix(config('waterhole.cp.path'))
                ->group(__DIR__ . '/../../routes/cp.php');
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('waterhole.create', function (Request $request) {
            if ($request->user()?->isAdmin() || !$request->input('commit')) {
                return Limit::none();
            }

            return Limit::perMinute(config('waterhole.forum.create_per_minute', 3))->by(
                $request->user()->id,
            );
        });

        RateLimiter::for('waterhole.search', function (Request $request) {
            if ($request->user()?->isAdmin() || !$request->input('q')) {
                return Limit::none();
            }

            return Limit::perMinute(config('waterhole.forum.search_per_minute', 10));
        });
    }
}
