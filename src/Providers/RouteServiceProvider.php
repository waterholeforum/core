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
        Route::middlewareGroup('waterhole.web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Waterhole\Http\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Tonysm\TurboLaravel\Http\Middleware\TurboMiddleware::class,
            \Waterhole\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Waterhole\Http\Middleware\ActorSeen::class,
            \Waterhole\Http\Middleware\Localize::class,
            \Waterhole\Http\Middleware\PoweredByHeader::class,
        ]);

        Route::middlewareGroup('waterhole.admin', [
            'can:administrate',
            'password.confirm:waterhole.confirm-password',
            \Waterhole\Http\Middleware\Admin\ContactOutpost::class,
        ]);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('waterhole.web')
                ->name('waterhole.')
                ->prefix(config('waterhole.forum.path'))
                ->group(__DIR__ . '/../../routes/web.php');

            Route::middleware(['waterhole.web', 'waterhole.admin'])
                ->name('waterhole.admin.')
                ->prefix(config('waterhole.admin.path'))
                ->group(__DIR__ . '/../../routes/admin.php');
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
