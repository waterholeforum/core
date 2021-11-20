<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Admin;

Route::get('/', Admin\HomeController::class)->name('home');

Route::get('settings', Admin\SettingsController::class)->name('settings');

Route::get('structure', [Admin\StructureController::class, 'index'])->name('structure');
Route::post('structure', [Admin\StructureController::class, 'saveOrder']);

Route::prefix('structure')->name('structure.')->group(function () {
    Route::resource('headings', Admin\StructureHeadingController::class)
        ->only('create', 'store', 'edit', 'update')
        ->parameter('heading', 'structure_heading');

    Route::resource('links', Admin\StructureLinkController::class)
        ->only('create', 'store', 'edit', 'update')
        ->parameter('link', 'structure_link');

    Route::resource('channels', Admin\ChannelController::class)
        ->only('create', 'store', 'edit', 'update');

    Route::resource('pages', Admin\PageController::class)
        ->only('create', 'store', 'edit', 'update');
});

Route::resource('groups', Admin\GroupController::class)
    ->only('index', 'create', 'store', 'edit', 'update');

Route::get('design', Admin\SettingsController::class)->name('design');

Route::get('users', Admin\SettingsController::class)->name('users');

Route::get('utilities', Admin\SettingsController::class)->name('utilities');

Route::get('extensions', Admin\SettingsController::class)->name('extensions');

Route::get('updates', Admin\SettingsController::class)->name('updates');
