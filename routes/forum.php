<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\ActionsController;
use Waterhole\Http\Controllers\Auth\ConfirmPasswordController;
use Waterhole\Http\Controllers\Auth\ForgotPasswordController;
use Waterhole\Http\Controllers\Auth\LoginController;
use Waterhole\Http\Controllers\Auth\LogoutController;
use Waterhole\Http\Controllers\Auth\RegisterController;
use Waterhole\Http\Controllers\Auth\ResetPasswordController;
use Waterhole\Http\Controllers\Auth\SsoController;
use Waterhole\Http\Controllers\Auth\VerifyEmailController;
use Waterhole\Http\Controllers\FormatController;
use Waterhole\Http\Controllers\Forum\CommentController;
use Waterhole\Http\Controllers\Forum\IndexController;
use Waterhole\Http\Controllers\Forum\NotificationController;
use Waterhole\Http\Controllers\Forum\PostController;
use Waterhole\Http\Controllers\Forum\PreferencesController;
use Waterhole\Http\Controllers\Forum\RssController;
use Waterhole\Http\Controllers\Forum\SearchController;
use Waterhole\Http\Controllers\Forum\UserController;
use Waterhole\Http\Controllers\ImpersonateController;
use Waterhole\Http\Controllers\UploadController;
use Waterhole\Http\Controllers\UserLookupController;

// Feed
Route::get('/', [IndexController::class, 'home'])->name('home');
Route::get('channels/{channel:slug}', [IndexController::class, 'channel'])->name('channels.show');
Route::get('pages/{page:slug}', [IndexController::class, 'page'])->name('page');

// RSS
Route::get('posts.rss', [RssController::class, 'posts'])->name('rss.posts');
Route::get('channels/{channel:slug}/posts.rss', [RssController::class, 'channel'])->name(
    'rss.channel',
);

// Actions
Route::get('actions/menu', [ActionsController::class, 'menu'])->name('actions.menu');
Route::get('actions/confirm', [ActionsController::class, 'confirm'])->name('actions.create');
Route::post('actions/run', [ActionsController::class, 'run'])->name('actions.store');

// Posts
Route::resource('posts', PostController::class)->only([
    'show',
    'create',
    'store',
    'edit',
    'update',
]);

// Comments
Route::resource('posts.comments', CommentController::class)
    ->only(['show', 'create', 'store', 'edit', 'update'])
    ->scoped();

// Reactions
Route::get('posts/{post}/reactions/{reactionType}', [PostController::class, 'reactions'])->name(
    'posts.reactions',
);
Route::get('comments/{comment}/reactions/{reactionType}', [
    CommentController::class,
    'reactions',
])->name('comments.reactions');

// Users
Route::get('users/{user}/posts', [UserController::class, 'posts'])->name('user.posts');
Route::get('users/{user}/comments', [UserController::class, 'comments'])->name('user.comments');
Route::resource('users', UserController::class)->only(['show']);

// Preferences
Route::get('preferences', [PreferencesController::class, 'index'])->name('preferences');

Route::get('preferences/account', [PreferencesController::class, 'account'])->name(
    'preferences.account',
);
Route::post('preferences/email', [PreferencesController::class, 'changeEmail'])->name(
    'preferences.email',
);

if (config('waterhole.auth.password_enabled', true)) {
    Route::post('preferences/password', [PreferencesController::class, 'changePassword'])->name(
        'preferences.password',
    );
}

Route::get('preferences/profile', [PreferencesController::class, 'profile'])->name(
    'preferences.profile',
);
Route::post('preferences/profile', [PreferencesController::class, 'saveProfile']);

Route::get('preferences/notifications', [PreferencesController::class, 'notifications'])->name(
    'preferences.notifications',
);
Route::post('preferences/notifications', [PreferencesController::class, 'saveNotifications']);

// Notifications
Route::get('notifications/unsubscribe', [NotificationController::class, 'unsubscribe'])->name(
    'notifications.unsubscribe',
);
Route::post('notifications/read', [NotificationController::class, 'read'])->name(
    'notifications.read',
);
Route::get('notifications/{notification}/go', [NotificationController::class, 'go'])->name(
    'notifications.go',
);
Route::resource('notifications', NotificationController::class)->only(['index', 'show']);

// Search
Route::get('search', SearchController::class)->name('search');

$authAvailable =
    count(config('waterhole.auth.providers', [])) ||
    config('waterhole.auth.password_enabled', true);

// Register
if (config('waterhole.auth.allow_registration', true) && $authAvailable) {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
}

Route::get('register/{payload}', [RegisterController::class, 'registerWithPayload'])->name(
    'register.payload',
);
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

// Login
if ($authAvailable) {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
}

if (config('waterhole.auth.password_enabled', true)) {
    Route::post('login', [LoginController::class, 'login']);

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name(
        'forgot-password',
    );
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

    // Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name(
        'reset-password',
    );
    Route::post('reset-password/{token}', [ResetPasswordController::class, 'reset']);
}

// Verify Email
Route::get('verify-email/{id}', [VerifyEmailController::class, 'verify'])->name('verify-email');
Route::post('verify-email', [VerifyEmailController::class, 'resend'])->name('verify-email.resend');

// Confirm Password
Route::get('confirm-password', [ConfirmPasswordController::class, 'showConfirmForm'])->name(
    'confirm-password',
);
Route::post('confirm-password', [ConfirmPasswordController::class, 'confirm']);

// Logout
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// SSO
Route::get('auth/{provider}', [SsoController::class, 'login'])->name('sso.login');
Route::get('auth/{provider}/callback', [SsoController::class, 'callback'])->name('sso.callback');

// Utils
Route::get('user-lookup/{post?}', UserLookupController::class)->name('user-lookup');
Route::post('format', FormatController::class)->name('format');
Route::post('upload', UploadController::class)->name('upload');
Route::get('impersonate/{user}', ImpersonateController::class)->name('impersonate');

resolve(Waterhole\Extend\Routing\ForumRoutes::class)->execute();
