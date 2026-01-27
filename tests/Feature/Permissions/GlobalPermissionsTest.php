<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('gate before', function () {
    test('unverified users are treated as guests', function () {
        $memberOnly = Channel::factory()->create();
        $memberOnly->savePermissions(['group:2' => ['view' => true]]);

        $public = Channel::factory()->public()->create();

        $user = User::factory()->create(['email_verified_at' => null]);

        expect(Gate::forUser($user)->allows('waterhole.channel.view', $memberOnly))->toBeFalse();
        expect(Gate::forUser($user)->allows('waterhole.channel.view', $public))->toBeTrue();
    });

    test('suspended users are treated as guests', function () {
        $memberOnly = Channel::factory()->create();
        $memberOnly->savePermissions(['group:2' => ['view' => true]]);

        $public = Channel::factory()->public()->create();

        $user = User::factory()->create(['suspended_until' => now()->addDay()]);

        expect(Gate::forUser($user)->allows('waterhole.channel.view', $memberOnly))->toBeFalse();
        expect(Gate::forUser($user)->allows('waterhole.channel.view', $public))->toBeTrue();
    });
});

describe('cp access', function () {
    test('non-admin users are forbidden', function () {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->get(route('waterhole.cp.dashboard'))->assertForbidden();
    });

    test('admins are allowed', function () {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)->get(route('waterhole.cp.dashboard'))->assertOk();
    });
});
