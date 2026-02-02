<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Group;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/users', function () {
    test('list users', function () {
        User::factory()->create();

        $this->actingAs(User::factory()->admin()->create());

        $response = jsonApi('GET', '/api/users');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    });

    test('retrieve user', function () {
        $user = User::factory()->create();

        $response = jsonApi('GET', "/api/users/$user->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'users', 'id' => $user->id]]);
    });

    test('hides private user fields from guests', function () {
        $user = User::factory()->create([
            'locale' => 'fr',
            'show_online' => false,
            'last_seen_at' => now()->subHour(),
            'suspended_until' => now()->addDay(),
        ]);

        $response = jsonApi('GET', "/api/users/$user->id");

        $response->assertOk();
        $response->assertJsonMissingPath('data.attributes.email');
        $response->assertJsonMissingPath('data.attributes.locale');
        $response->assertJsonMissingPath('data.attributes.lastSeenAt');
        $response->assertJsonMissingPath('data.attributes.suspendedUntil');
    });

    test('shows private user fields to the user', function () {
        $user = User::factory()->create([
            'locale' => 'fr',
            'show_online' => false,
            'last_seen_at' => now()->subHour(),
            'suspended_until' => now()->addDay(),
        ]);

        $this->actingAs($user);

        $response = jsonApi('GET', "/api/users/$user->id");

        $response->assertOk();
        $response->assertJsonPath('data.attributes.email', $user->email);
        $response->assertJsonPath('data.attributes.locale', $user->locale);
        $response->assertJsonPath(
            'data.attributes.lastSeenAt',
            $user->last_seen_at->toIso8601String(),
        );
        $response->assertJsonPath(
            'data.attributes.suspendedUntil',
            $user->suspended_until->toIso8601String(),
        );
    });

    test('shows private user fields to admins', function () {
        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $user = User::factory()->create([
            'locale' => 'fr',
            'show_online' => false,
            'last_seen_at' => now()->subHour(),
            'suspended_until' => now()->addDay(),
        ]);

        $this->actingAs($admin);

        $response = jsonApi('GET', "/api/users/$user->id");

        $response->assertOk();
        $response->assertJsonPath('data.attributes.email', $user->email);
        $response->assertJsonPath('data.attributes.locale', $user->locale);
        $response->assertJsonPath(
            'data.attributes.lastSeenAt',
            $user->last_seen_at->toIso8601String(),
        );
        $response->assertJsonPath(
            'data.attributes.suspendedUntil',
            $user->suspended_until->toIso8601String(),
        );
    });
});
