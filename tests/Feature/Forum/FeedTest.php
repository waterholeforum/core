<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Feed\Feed;
use Waterhole\Filters\Latest;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;

uses(RefreshDatabase::class);

test('cursor pagination returns every item when filter values are duplicated', function () {
    config(['waterhole.forum.posts_per_page' => 2]);

    $channel = Channel::factory()->create();
    $posts = Post::factory()
        ->count(5)
        ->for($channel)
        ->create(['last_activity_at' => now()]);

    $feed = new Feed(request(), Post::withoutGlobalScopes(), [new Latest()]);
    $ids = collect();

    do {
        $page = $feed->items();
        $ids->push(...$page->pluck('id'));
        request()->query->set('cursor', $page->nextCursor()?->encode());
    } while ($page->hasMorePages());

    expect($ids)->toHaveCount($posts->count())->toEqualCanonicalizing($posts->pluck('id'));
});
