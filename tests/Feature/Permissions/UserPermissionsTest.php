<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('gate', function () {
    test('users can suspend others only when they have permission', function () {
        $actor = User::factory()->create();
        $target = User::factory()->create();

        $actor->savePermissions(['user' => ['suspend' => true]]);

        expect(Gate::forUser($actor)->allows('waterhole.user.suspend', $target))->toBeTrue();
    });

    test('users cannot suspend other users with the same permission', function () {
        $actor = User::factory()->create();
        $target = User::factory()->create();

        $actor->savePermissions(['user' => ['suspend' => true]]);
        $target->savePermissions(['user' => ['suspend' => true]]);

        expect(Gate::forUser($actor)->allows('waterhole.user.suspend', $target))->toBeFalse();
    });
});
