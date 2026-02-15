<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpUsersAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('cp users', function () {
    test('set email verification state on user create', function (bool $emailVerified) {
        $admin = cpUsersAdmin();

        $this->actingAs($admin)
            ->post(route('waterhole.cp.users.store'), [
                'name' => fake()->userName(),
                'email' => fake()->safeEmail(),
                'password' => 'password',
                'email_verified' => $emailVerified ? 1 : 0,
                'show_online' => 1,
            ])
            ->assertRedirect(route('waterhole.cp.users.index', ['sort' => 'created_at']));

        $created = User::latest('id')->firstOrFail();

        expect($created->hasVerifiedEmail())->toBe($emailVerified);
    })->with([
        'verified' => [true],
        'unverified' => [false],
    ]);

    test('toggle email verification state on user update', function (
        bool $initiallyVerified,
        bool $emailVerified,
    ) {
        $admin = cpUsersAdmin();
        $user = User::factory()->create([
            'email_verified_at' => $initiallyVerified ? now()->subDay() : null,
        ]);

        $this->actingAs($admin)
            ->put(route('waterhole.cp.users.update', $user), [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => $emailVerified ? 1 : 0,
                'show_online' => 1,
            ])
            ->assertRedirect(route('waterhole.cp.users.index'));

        $user->refresh();

        expect($user->hasVerifiedEmail())->toBe($emailVerified);
    })->with([
        'unverify' => [true, false],
        'verify' => [false, true],
    ]);

    test('update user fields', function () {
        $admin = cpUsersAdmin();
        $user = User::factory()->create(['name' => 'Old Name']);

        $this->actingAs($admin)
            ->put(route('waterhole.cp.users.update', $user), [
                'name' => 'New Name',
                'email' => $user->email,
                'show_online' => 1,
            ])
            ->assertRedirect(route('waterhole.cp.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    });

    test('impersonate user', function () {
        $admin = cpUsersAdmin();
        $target = User::factory()->create();

        $url = URL::signedRoute('waterhole.impersonate', ['user' => $target]);

        $this->actingAs($admin)->get($url)->assertRedirect(route('waterhole.home'));

        $this->assertAuthenticatedAs($target);
    });
});
