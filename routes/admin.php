<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Admin;

Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
Route::get('widget/{id}', [Admin\DashboardController::class, 'widget'])->name('dashboard.widget');

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
