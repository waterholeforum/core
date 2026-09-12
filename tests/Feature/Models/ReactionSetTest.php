<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Once;
use Waterhole\Models\ReactionSet;

uses(RefreshDatabase::class);

test('default reaction sets are cached only for the current request lifecycle', function () {
    Once::flush();
    $set = ReactionSet::create([
        'name' => 'Default',
        'is_default_posts' => true,
        'is_default_comments' => true,
    ]);

    $default = ReactionSet::defaultPosts();
    expect($default->id)->toBe($set->id);

    DB::enableQueryLog();
    DB::flushQueryLog();

    expect(ReactionSet::defaultPosts())
        ->toBe($default)
        ->and(ReactionSet::defaultComments())
        ->toBe($default)
        ->and(DB::getQueryLog())
        ->toBeEmpty();

    DB::disableQueryLog();

    $set->update(['is_default_posts' => false]);
    Once::flush();

    expect(ReactionSet::defaultPosts())
        ->toBeNull()
        ->and(ReactionSet::defaultComments()->id)
        ->toBe($set->id);
});
