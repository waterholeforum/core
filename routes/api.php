<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers;

// Route::get('user', Controllers\Api\CurrentUser::class);

// Route::post('users/{id}/avatar', [Controllers\Api\AvatarController::class, 'upload']);
// Route::delete('users/{id}/avatar', [Controllers\Api\AvatarController::class, 'remove']);

resolve(Waterhole\Extend\Routing\ApiRoutes::class)->execute();

Route::any('{uri?}', Controllers\Api\ApiController::class)
    ->where('uri', '.*')
    ->name('main');
