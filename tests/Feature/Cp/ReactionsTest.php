<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\Delete;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpReactionsAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('cp reactions', function () {
    test('create reaction set', function () {
        $this->actingAs(cpReactionsAdmin())
            ->post(route('waterhole.cp.reaction-sets.store'), [
                'name' => 'Emoji Set',
                'is_default_posts' => 0,
                'is_default_comments' => 0,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reaction_sets', ['name' => 'Emoji Set']);
    });

    test('update reaction set', function () {
        $set = ReactionSet::create(['name' => 'Old']);

        $this->actingAs(cpReactionsAdmin())
            ->put(route('waterhole.cp.reaction-sets.update', $set), [
                'name' => 'New',
                'is_default_posts' => 0,
                'is_default_comments' => 0,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reaction_sets', ['id' => $set->id, 'name' => 'New']);
    });

    test('delete reaction set', function () {
        $set = ReactionSet::create(['name' => 'Delete Set']);

        $this->actingAs(cpReactionsAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => ReactionSet::class,
                'id' => $set->id,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('reaction_sets', ['id' => $set->id]);
    });

    test('create reaction type', function () {
        $set = ReactionSet::create(['name' => 'Set']);

        $this->actingAs(cpReactionsAdmin())
            ->post(route('waterhole.cp.reaction-sets.reaction-types.store', $set), [
                'name' => 'Like',
                'icon' => ['type' => null],
                'score' => 1,
            ])
            ->assertRedirect($set->edit_url);

        $this->assertDatabaseHas('reaction_types', [
            'reaction_set_id' => $set->id,
            'name' => 'Like',
        ]);
    });

    test('update reaction type', function () {
        $set = ReactionSet::create(['name' => 'Set']);
        $type = ReactionType::create([
            'reaction_set_id' => $set->id,
            'name' => 'Old Type',
            'score' => 1,
            'position' => 0,
        ]);

        $this->actingAs(cpReactionsAdmin())
            ->put(route('waterhole.cp.reaction-sets.reaction-types.update', [$set, $type]), [
                'name' => 'New Type',
                'icon' => ['type' => null],
                'score' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reaction_types', ['id' => $type->id, 'name' => 'New Type']);
    });

    test('delete reaction type', function () {
        $set = ReactionSet::create(['name' => 'Set']);
        $type = ReactionType::create([
            'reaction_set_id' => $set->id,
            'name' => 'Delete Type',
            'score' => 1,
            'position' => 0,
        ]);

        $this->actingAs(cpReactionsAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => ReactionType::class,
                'id' => $type->id,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('reaction_types', ['id' => $type->id]);
    });
});
