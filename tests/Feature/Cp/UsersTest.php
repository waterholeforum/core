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
