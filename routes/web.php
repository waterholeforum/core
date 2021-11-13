<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\ActionController;
use Waterhole\Http\Controllers\Auth\ConfirmPasswordController;
use Waterhole\Http\Controllers\Auth\ForgotPasswordController;
use Waterhole\Http\Controllers\Auth\LoginController;
use Waterhole\Http\Controllers\Auth\LogoutController;
use Waterhole\Http\Controllers\Auth\RegisterController;
use Waterhole\Http\Controllers\Auth\ResetPasswordController;
use Waterhole\Http\Controllers\Auth\VerifyEmailController;
use Waterhole\Http\Controllers\FormatController;
use Waterhole\Http\Controllers\Forum\ChannelController;
use Waterhole\Http\Controllers\Forum\CommentController;
use Waterhole\Http\Controllers\Forum\HomeController;
use Waterhole\Http\Controllers\Forum\NotificationController;
use Waterhole\Http\Controllers\Forum\PostController;
use Waterhole\Http\Controllers\Forum\PreferencesController;
use Waterhole\Http\Controllers\Forum\SearchController;
use Waterhole\Http\Controllers\Forum\UserController;
use Waterhole\Http\Controllers\UserLookupController;

// Home
Route::get('/', HomeController::class)->name('home');

// Actions
Route::get('confirm-action', [ActionController::class, 'confirm'])->name('action.create');
Route::post('action', [ActionController::class, 'run'])->name('action.store');

// Posts
Route::resource('posts', PostController::class)
    ->only(['show', 'create', 'store', 'edit', 'update']);

// Comments
Route::resource('posts.comments', CommentController::class)
    ->only(['show', 'create', 'store', 'edit', 'update'])
    ->scoped();

// Channels
Route::resource('channels', ChannelController::class)
    ->only(['show', 'create', 'store', 'edit', 'update'])
    ->scoped(['channel' => 'slug']);

// Users
Route::get('users/{user}/posts', [UserController::class, 'posts'])->name('user.posts');
Route::get('users/{user}/comments', [UserController::class, 'comments'])->name('user.comments');
Route::resource('users', UserController::class)
    ->only(['show'])
    ->scoped(['user' => 'name']);

// Preferences
Route::get('preferences', [PreferencesController::class, 'index'])->name('preferences');

Route::get('preferences/account', [PreferencesController::class, 'account'])->name('preferences.account');
Route::post('preferences/email', [PreferencesController::class, 'changeEmail'])->name('preferences.email');
Route::post('preferences/password', [PreferencesController::class, 'changePassword'])->name('preferences.password');

Route::get('preferences/profile', [PreferencesController::class, 'profile'])->name('preferences.profile');
Route::post('preferences/profile', [PreferencesController::class, 'saveProfile']);

Route::get('preferences/notifications', [PreferencesController::class, 'notifications'])->name('preferences.notifications');
Route::post('preferences/notifications', [PreferencesController::class, 'saveNotifications'])->name('preferences.notifications');

// Notifications
Route::get('notifications/unsubscribe', [NotificationController::class, 'unsubscribe'])->name('notifications.unsubscribe');
Route::post('notifications/read', [NotificationController::class, 'read'])->name('notifications.read');
Route::resource('notifications', NotificationController::class)->only(['index', 'show']);

// Search
Route::get('search', SearchController::class)->name('search');

// Register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('reset-password');
Route::post('reset-password/{token}', [ResetPasswordController::class, 'reset']);

Route::get('verify-email/{id}', [VerifyEmailController::class, 'verify'])->name('verify-email');
Route::post('verify-email', [VerifyEmailController::class, 'resend'])->name('verify-email.resend');

Route::get('/confirm-password', [ConfirmPasswordController::class, 'showConfirmForm'])->name('confirm-password');
Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm']);

Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// Utils
Route::get('user-lookup', UserLookupController::class)->name('user-lookup');
Route::post('format', FormatController::class)->name('format');
