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
        Route::aliasMiddleware('waterhole.auth', \Waterhole\Http\Middleware\Authenticate::class);

        Route::aliasMiddleware(
            'waterhole.guest',
            \Waterhole\Http\Middleware\RedirectIfAuthenticated::class,
        );

        Route::aliasMiddleware(
            'waterhole.confirm-password',
            \Waterhole\Http\Middleware\MaybeRequirePassword::class,
        );

        Route::middlewareGroup('waterhole.web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Waterhole\Http\Middleware\AuthenticateWaterhole::class,
            \Waterhole\Http\Middleware\AuthGuard::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \HotwiredLaravel\TurboLaravel\Http\Middleware\TurboMiddleware::class,
            \Waterhole\Http\Middleware\ContactOutpost::class,
            \Waterhole\Http\Middleware\ActorSeen::class,
            \Waterhole\Http\Middleware\Localize::class,
            \Waterhole\Http\Middleware\PoweredByHeader::class,
        ]);

        Route::middlewareGroup('waterhole.cp', [
            'waterhole.auth',
            \Illuminate\Auth\Middleware\Authorize::using('waterhole.administrate'),
            \Waterhole\Http\Middleware\MaybeRequirePassword::class,
        ]);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['waterhole.web'])
                ->name('waterhole.')
                ->domain(config('waterhole.system.domain'))
                ->prefix(config('waterhole.forum.path'))
                ->group(__DIR__ . '/../../routes/forum.php');

            Route::middleware(['waterhole.web', 'waterhole.cp'])
                ->name('waterhole.cp.')
                ->domain(config('waterhole.system.domain'))
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
