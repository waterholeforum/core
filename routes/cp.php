<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Cp;

Route::get('/', [Cp\DashboardController::class, 'index'])->name('dashboard');
Route::get('widget/{id}', [Cp\DashboardController::class, 'widget'])->name('dashboard.widget');

Route::get('structure', [Cp\StructureController::class, 'index'])->name('structure');
Route::post('structure', [Cp\StructureController::class, 'saveOrder']);

Route::prefix('structure')
    ->name('structure.')
    ->group(function () {
        Route::resource('headings', Cp\StructureHeadingController::class)
            ->only('create', 'store', 'edit', 'update')
            ->parameter('heading', 'structure_heading');

        Route::resource('links', Cp\StructureLinkController::class)
            ->only('create', 'store', 'edit', 'update')
            ->parameter('link', 'structure_link');

        Route::resource('channels', Cp\ChannelController::class)->only(
            'create',
            'store',
            'edit',
            'update',
        );

        Route::resource('pages', Cp\PageController::class)->only(
            'create',
            'store',
            'edit',
            'update',
        );
    });

Route::resource('taxonomies', Cp\TaxonomyController::class)->only(
    'index',
    'create',
    'store',
    'edit',
    'update',
);

Route::resource('taxonomies.tags', Cp\TagController::class)
    ->only('create', 'store', 'edit', 'update')
    ->scoped();

Route::resource('groups', Cp\GroupController::class)->only(
    'index',
    'create',
    'store',
    'edit',
    'update',
);

Route::resource('users', Cp\UserController::class)->only(
    'index',
    'create',
    'store',
    'edit',
    'update',
);

Route::resource('reaction-sets', Cp\ReactionSetController::class)
    ->only('index', 'create', 'store', 'edit', 'update')
    ->parameters(['reaction-sets' => 'reactionSet']);

Route::resource('reaction-sets.reaction-types', Cp\ReactionTypeController::class)
    ->only('create', 'store', 'edit', 'update')
    ->parameters(['reaction-sets' => 'reactionSet', 'reaction-types' => 'reactionType'])
    ->scoped();

resolve(Waterhole\Extend\Routing\CpRoutes::class);
