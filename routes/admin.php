<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Admin;

Route::get('/', Admin\HomeController::class)->name('home');

Route::get('settings', Admin\SettingsController::class)->name('settings');

Route::get('structure', [Admin\StructureController::class, 'index'])->name('structure');
Route::post('structure', [Admin\StructureController::class, 'saveOrder']);

Route::prefix('structure')->name('structure.')->group(function () {
    Route::resource('headings', Admin\StructureHeadingController::class)
        ->except('index', 'show')
        ->parameter('heading', 'structure_heading');

    Route::resource('channels', Admin\ChannelController::class)
        ->except('index', 'show');

    Route::resource('pages', Admin\PageController::class)
        ->except('index', 'show');
});

Route::resource('groups', Admin\GroupController::class)
    ->only('index', 'create', 'store', 'edit', 'update');

Route::get('design', Admin\SettingsController::class)->name('design');

Route::get('users', Admin\SettingsController::class)->name('users');

Route::get('utilities', Admin\SettingsController::class)->name('utilities');

Route::get('extensions', Admin\SettingsController::class)->name('extensions');

Route::get('updates', Admin\SettingsController::class)->name('updates');
