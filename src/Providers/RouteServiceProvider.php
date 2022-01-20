<?php

namespace Waterhole\Providers;

use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Waterhole\Http\Middleware\Authenticate;

class RouteServiceProvider extends ServiceProvider
{
    const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::middlewareGroup('waterhole.web', [
            \Waterhole\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Tonysm\TurboLaravel\Http\Middleware\TurboMiddleware::class,
            \Waterhole\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Waterhole\Http\Middleware\ActorSeen::class,
            \Waterhole\Http\Middleware\Localize::class,
        ]);
        
        Route::aliasMiddleware('waterhole.auth', Authenticate::class);
        Route::aliasMiddleware('waterhole.throttle', ThrottleRequests::class);
        Route::aliasMiddleware('waterhole.confirm-password', RequirePassword::class);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('waterhole.web')
                ->name('waterhole.')
                ->prefix(config('waterhole.forum.path'))
                ->group(__DIR__.'/../../routes/web.php');

            Route::middleware(['waterhole.web', 'waterhole.confirm-password:waterhole.confirm-password'])
                ->name('waterhole.admin.')
                ->prefix(config('waterhole.admin.path'))
                ->group(__DIR__.'/../../routes/admin.php');
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('waterhole.create', function (Request $request) {
            // return Limit::perMinute(2)->by($request->user()->id);
        });

        RateLimiter::for('waterhole.search', function (Request $request) {
            return $request->input('q') ? Limit::perMinute(10) : Limit::none();
        });
    }
}
