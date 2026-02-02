<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\SuspendUser;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('user profile', function () {
    test('shows user profile page', function () {
        $user = User::factory()->create();

        $this->get(route('waterhole.users.show', $user))->assertRedirect(
            route('waterhole.user.posts', $user),
        );

        $this->get(route('waterhole.user.posts', $user))->assertOk()->assertSeeText($user->name);
    });
});

describe('suspension', function () {
    test('moderator can suspend user', function () {
        $moderator = User::factory()->create();
        $target = User::factory()->create();

        $moderator->savePermissions(['user' => ['suspend' => true]]);

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => User::class,
                'id' => $target->id,
                'action_class' => SuspendUser::class,
                'confirmed' => true,
                'status' => 'indefinite',
            ])
            ->assertRedirect();

        expect($target->fresh()->suspended_until?->toDateString())->toBe('2038-01-01');
    });

    test('moderator can unsuspend user', function () {
        $moderator = User::factory()->create();
        $target = User::factory()->create([
            'suspended_until' => now()->addWeek(),
        ]);

        $moderator->savePermissions(['user' => ['suspend' => true]]);

        $this->actingAs($moderator)
            ->post(route('waterhole.actions.store'), [
                'actionable' => User::class,
                'id' => $target->id,
                'action_class' => SuspendUser::class,
                'confirmed' => true,
                'status' => 'none',
            ])
            ->assertRedirect();

        expect($target->fresh()->suspended_until)->toBeNull();
    });
});
