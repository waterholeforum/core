<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Once;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Group;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

test('built-in groups are cached only for the current request lifecycle', function (
    string $method,
    int $id,
) {
    Once::flush();
    $group = Group::$method();
    expect($group->id)->toBe($id);

    DB::enableQueryLog();
    DB::flushQueryLog();
    expect(Group::$method())->toBe($group)->and(DB::getQueryLog())->toBeEmpty();
    DB::disableQueryLog();

    Group::whereKey($id)->update(['name' => 'Updated Group']);
    Once::flush();

    expect(Group::$method()->name)->toBe('Updated Group');
})->with([
    ['guest',  Group::GUEST_ID],
    ['member', Group::MEMBER_ID],
    ['admin',  Group::ADMIN_ID],
]);
