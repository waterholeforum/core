<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\Delete;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Group;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpGroupsAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('cp groups', function () {
    test('create group', function () {
        $this->actingAs(cpGroupsAdmin())
            ->post(route('waterhole.cp.groups.store'), [
                'name' => 'CP Group',
                'icon' => ['type' => null],
                'is_public' => 0,
                'auto_assign' => 0,
                'rules' => [
                    'requires_approval' => 0,
                    'remove_after_approval' => 0,
                ],
            ])
            ->assertRedirect(route('waterhole.cp.groups.index'));

        $this->assertDatabaseHas('groups', ['name' => 'CP Group']);
    });

    test('update group', function () {
        $group = Group::create(['name' => 'Old Name']);

        $this->actingAs(cpGroupsAdmin())
            ->put(route('waterhole.cp.groups.update', $group), [
                'name' => 'New Name',
                'icon' => ['type' => null],
                'is_public' => 0,
                'auto_assign' => 0,
                'rules' => [
                    'requires_approval' => 0,
                    'remove_after_approval' => 0,
                ],
            ])
            ->assertRedirect(route('waterhole.cp.groups.index'));

        $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => 'New Name']);
    });

    test('delete group', function () {
        $group = Group::create(['name' => 'Delete Group']);

        $this->actingAs(cpGroupsAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Group::class,
                'id' => $group->id,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    });

    test('cannot delete built-in group', function () {
        $this->actingAs(cpGroupsAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Group::class,
                'id' => Group::GUEST_ID,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertForbidden();
    });

    test('updates group permissions', function () {
        $group = Group::create(['name' => 'Permissions Group']);

        $this->actingAs(cpGroupsAdmin())
            ->put(route('waterhole.cp.groups.update', $group), [
                'name' => 'Permissions Group',
                'icon' => ['type' => null],
                'is_public' => 0,
                'auto_assign' => 0,
                'rules' => [
                    'requires_approval' => 0,
                    'remove_after_approval' => 0,
                ],
                'permissions' => [
                    'user' => ['suspend' => true],
                ],
            ])
            ->assertRedirect(route('waterhole.cp.groups.index'));

        $this->assertDatabaseHas('permissions', [
            'recipient_type' => $group->getMorphClass(),
            'recipient_id' => $group->id,
            'scope_type' => 'user',
            'ability' => 'suspend',
        ]);
    });
});
