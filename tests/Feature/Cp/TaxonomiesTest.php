<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\Delete;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpTaxonomiesAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('cp taxonomies', function () {
    test('create taxonomy', function () {
        $this->actingAs(cpTaxonomiesAdmin())
            ->post(route('waterhole.cp.taxonomies.store'), [
                'name' => 'Topics',
                'is_required' => false,
                'allow_multiple' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('taxonomies', ['name' => 'Topics']);
    });

    test('update taxonomy', function () {
        $taxonomy = Taxonomy::create(['name' => 'Old']);

        $this->actingAs(cpTaxonomiesAdmin())
            ->put(route('waterhole.cp.taxonomies.update', $taxonomy), [
                'name' => 'New',
                'is_required' => true,
                'allow_multiple' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('taxonomies', ['id' => $taxonomy->id, 'name' => 'New']);
    });

    test('delete taxonomy', function () {
        $taxonomy = Taxonomy::create(['name' => 'Delete']);

        $this->actingAs(cpTaxonomiesAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Taxonomy::class,
                'id' => $taxonomy->id,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('taxonomies', ['id' => $taxonomy->id]);
    });

    test('create tag', function () {
        $taxonomy = Taxonomy::create(['name' => 'Topics']);

        $this->actingAs(cpTaxonomiesAdmin())
            ->post(route('waterhole.cp.taxonomies.tags.store', $taxonomy), [
                'name' => 'Feature',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tags', ['taxonomy_id' => $taxonomy->id, 'name' => 'Feature']);
    });

    test('update tag', function () {
        $taxonomy = Taxonomy::create(['name' => 'Topics']);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Old Tag']);

        $this->actingAs(cpTaxonomiesAdmin())
            ->put(route('waterhole.cp.taxonomies.tags.update', [$taxonomy, $tag]), [
                'name' => 'New Tag',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'New Tag']);
    });

    test('delete tag', function () {
        $taxonomy = Taxonomy::create(['name' => 'Topics']);
        $tag = Tag::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Delete Tag']);

        $this->actingAs(cpTaxonomiesAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Tag::class,
                'id' => $tag->id,
                'action_class' => Delete::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    });
});
