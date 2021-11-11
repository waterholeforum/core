<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\Admin;

Route::get('/', Admin\HomeController::class)->name('home');
Route::get('settings', Admin\SettingsController::class)->name('settings');
Route::get('structure', Admin\SettingsController::class)->name('structure');
Route::get('design', Admin\SettingsController::class)->name('design');
Route::get('users', Admin\SettingsController::class)->name('users');
Route::get('permissions', Admin\SettingsController::class)->name('permissions');
Route::get('utilities', Admin\SettingsController::class)->name('utilities');
Route::get('extensions', Admin\SettingsController::class)->name('extensions');
Route::get('updates', Admin\SettingsController::class)->name('updates');
