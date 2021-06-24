<?php

namespace Waterhole\Providers;

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
            \Waterhole\Http\Middleware\SightActor::class,
            // \Waterhole\Http\Middleware\SetLocale::class is this not built into Laravel?
        ]);

        Route::aliasMiddleware('waterhole.auth', Authenticate::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('waterhole.web')
            ->name('waterhole.')
            ->prefix(config('waterhole.forum.route'))
            ->group(__DIR__.'/../../routes/web.php');
    }
}
