<?php

namespace Waterhole\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Waterhole\Http\Middleware\Authenticate;
use Waterhole\Models\Channel;

class RouteServiceProvider extends ServiceProvider
{
    const HOME = '';

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

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('waterhole.web')
                ->name('waterhole.')
                ->prefix(config('waterhole.forum.route'))
                ->group(__DIR__.'/../../routes/web.php');
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('waterhole.create', function (Request $request) {
            // return Limit::perMinute(2)->by($request->user()->id);
        });
    }
}
