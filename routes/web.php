<?php

use Illuminate\Support\Facades\Route;
use Waterhole\Http\Controllers\ActionController;
use Waterhole\Http\Controllers\FormatController;
use Waterhole\Http\Controllers\Forum\ChannelController;
use Waterhole\Http\Controllers\Forum\CommentController;
use Waterhole\Http\Controllers\Forum\HomeController;
use Waterhole\Http\Controllers\Forum\PostController;
use Waterhole\Http\Controllers\UserLookupController;

Route::get('/', HomeController::class)->name('home');

Route::get('confirm-action', [ActionController::class, 'confirm'])->name('action.create');
Route::post('action', [ActionController::class, 'run'])->name('action.store');

Route::resource('posts', PostController::class)
    ->only(['show', 'create', 'store', 'edit', 'update']);

Route::resource('posts.comments', CommentController::class)
    ->only(['show', 'store', 'edit', 'update'])
    ->scoped();

Route::resource('channels', ChannelController::class)
    ->only(['show', 'create', 'store', 'edit', 'update'])
    ->scoped(['channel' => 'slug']);

Route::get('user-lookup', UserLookupController::class)->name('user-lookup');
Route::post('format', FormatController::class)->name('format');

// Route::get('channels/{channel:slug}/delete', [ChannelController::class, 'delete'])->name('channels.delete');


// $discussionListFilters = implode('|', array_keys(DiscussionListFilter::getItems()));
//
// Route::get('{filter?}', 'DiscussionListController')->where('filter', $discussionListFilters)->name('discussionList');
// Route::get('c/{slug}/{filter?}', 'DiscussionListController')->where('filter', $discussionListFilters)->name('discussionList.category');
//
// foreach (DiscussionListRoute::getItems() as $path => $filter) {
//     $route = Route::get($path.'/{filter?}', 'DiscussionListController')->where('filter', $discussionListFilters)->name('discussionList.'.$path);
// }
//
// Route::get('categories', 'CategoriesController')->name('categories');
//
// Route::get('d/new', 'NewDiscussionController@show')->name('discussion.new');
// Route::post('d/new', 'NewDiscussionController@post');
//
// Route::get('d/{category}/{id}-{slug?}', 'DiscussionController@show')->name('discussion');
// Route::get('post/{post}', 'PostController@show')->name('post');
//
// $userPostsFilters = implode('|', array_keys(app('waterhole.userPostsFilters')));
//
// Route::get('u/{username}', 'User')->name('user');
// Route::get('u/{username}/{filter?}', 'User')->where('filter', $userPostsFilters)->name('user.posts');
// Route::get('u/{username}/discussions/{filter?}', 'User')->where('filter', $discussionListFilters)->name('user.discussions');
//
// Route::get('unsubscribe/{actor}/{type}', 'Unsubscribe@type')->name('unsubscribe.notifications')->middleware('signed');
// Route::get('unsubscribe/{actor}/discussion/{discussion}', 'Unsubscribe@discussion')->name('unsubscribe.discussion')->middleware('signed');
// Route::get('unsubscribe/{actor}/category/{category}', 'Unsubscribe@category')->name('unsubscribe.category')->middleware('signed');

require __DIR__.'/auth.php';
