<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Admin;
use Waterhole\Http\Controllers\Admin\LicenseController;

Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
Route::get('widget/{id}', [Admin\DashboardController::class, 'widget'])->name('dashboard.widget');
Route::get('license', LicenseController::class)->name('license');

Route::get('structure', [Admin\StructureController::class, 'index'])->name('structure');
Route::post('structure', [Admin\StructureController::class, 'saveOrder']);

Route::prefix('structure')
    ->name('structure.')
    ->group(function () {
        Route::resource('headings', Admin\StructureHeadingController::class)
            ->only('create', 'store', 'edit', 'update')
            ->parameter('heading', 'structure_heading');

        Route::resource('links', Admin\StructureLinkController::class)
            ->only('create', 'store', 'edit', 'update')
            ->parameter('link', 'structure_link');

        Route::resource('channels', Admin\ChannelController::class)->only(
            'create',
            'store',
            'edit',
            'update',
        );

        Route::resource('pages', Admin\PageController::class)->only(
            'create',
            'store',
            'edit',
            'update',
        );
    });

Route::resource('groups', Admin\GroupController::class)->only(
    'index',
    'create',
    'store',
    'edit',
    'update',
);

Route::resource('users', Admin\UserController::class)->only(
    'index',
    'create',
    'store',
    'edit',
    'update',
);

Route::resource('reaction-sets', Admin\ReactionSetController::class)
    ->only('index', 'create', 'store', 'edit', 'update')
    ->parameters(['reaction-sets' => 'reactionSet']);

Route::resource('reaction-sets.reaction-types', Admin\ReactionTypeController::class)
    ->only('create', 'store', 'edit', 'update')
    ->parameters(['reaction-sets' => 'reactionSet', 'reaction-types' => 'reactionType'])
    ->scoped();

Route::post('reaction-sets/{reactionSet}/reaction-types/reorder', [
    Admin\ReactionTypeController::class,
    'reorder',
])->name('reaction-sets.reaction-types.reorder');
