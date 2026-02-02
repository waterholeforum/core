<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Actions\DeleteChannel;
use Waterhole\Actions\DeleteStructure;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function cpStructureAdmin(): User
{
    return User::factory()->admin()->create();
}

describe('cp channels', function () {
    test('create channel', function () {
        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.cp.structure.channels.store'), [
                'name' => 'CP Channel',
                'slug' => 'cp-channel',
                'icon' => ['type' => null],
                'ignore' => 0,
                'answerable' => 0,
                'show_similar_posts' => 0,
                'require_approval_posts' => 0,
                'require_approval_comments' => 0,
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('channels', ['name' => 'CP Channel', 'slug' => 'cp-channel']);
    });

    test('update channel', function () {
        $channel = Channel::factory()
            ->public()
            ->create(['name' => 'Old', 'slug' => 'old']);

        $this->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.channels.update', $channel), [
                'name' => 'New',
                'slug' => 'new',
                'icon' => ['type' => null],
                'ignore' => 0,
                'answerable' => 0,
                'show_similar_posts' => 0,
                'require_approval_posts' => 0,
                'require_approval_comments' => 0,
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('channels', [
            'id' => $channel->id,
            'name' => 'New',
            'slug' => 'new',
        ]);
    });

    test('delete channel', function () {
        $channel = Channel::factory()->public()->create();

        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Channel::class,
                'id' => $channel->id,
                'action_class' => DeleteChannel::class,
                'confirmed' => true,
                'move_posts' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('channels', ['id' => $channel->id]);
    });
});

describe('cp pages', function () {
    test('create page', function () {
        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.cp.structure.pages.store'), [
                'name' => 'CP Page',
                'slug' => 'cp-page',
                'icon' => ['type' => null],
                'body' => 'Body text',
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('pages', ['name' => 'CP Page', 'slug' => 'cp-page']);
    });

    test('update page', function () {
        $page = Page::factory()
            ->public()
            ->create(['name' => 'Old', 'slug' => 'old']);

        $this->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.pages.update', $page), [
                'name' => 'New',
                'slug' => 'new',
                'icon' => ['type' => null],
                'body' => 'Updated body',
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('pages', ['id' => $page->id, 'name' => 'New', 'slug' => 'new']);
    });

    test('delete page', function () {
        $page = Page::factory()->public()->create();

        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.actions.store'), [
                'actionable' => Page::class,
                'id' => $page->id,
                'action_class' => DeleteStructure::class,
                'confirmed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    });
});

describe('cp structure links and headings', function () {
    test('create structure heading', function () {
        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.cp.structure.headings.store'), ['name' => 'Heading'])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('structure_headings', ['name' => 'Heading']);
    });

    test('create structure link', function () {
        $this->actingAs(cpStructureAdmin())
            ->post(route('waterhole.cp.structure.links.store'), [
                'name' => 'Docs',
                'url' => 'https://example.com/docs',
                'icon' => ['type' => null],
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('structure_links', ['name' => 'Docs']);
    });

    test('update structure heading/link', function () {
        $heading = StructureHeading::create(['name' => 'Old Heading']);
        $link = StructureLink::create(['name' => 'Old Link', 'href' => 'https://old.test']);

        $this->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.headings.update', $heading), [
                'name' => 'New Heading',
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.links.update', $link), [
                'name' => 'New Link',
                'url' => 'https://new.test',
                'icon' => ['type' => null],
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('structure_headings', [
            'id' => $heading->id,
            'name' => 'New Heading',
        ]);
        $this->assertDatabaseHas('structure_links', ['id' => $link->id, 'name' => 'New Link']);
    });

    test('delete structure heading/link', function () {
        $heading = StructureHeading::create(['name' => 'Delete Heading']);
        $link = StructureLink::create(['name' => 'Delete Link', 'href' => 'https://delete.test']);

        $this->actingAs(cpStructureAdmin())->post(route('waterhole.actions.store'), [
            'actionable' => StructureHeading::class,
            'id' => $heading->id,
            'action_class' => DeleteStructure::class,
            'confirmed' => true,
        ]);

        $this->actingAs(cpStructureAdmin())->post(route('waterhole.actions.store'), [
            'actionable' => StructureLink::class,
            'id' => $link->id,
            'action_class' => DeleteStructure::class,
            'confirmed' => true,
        ]);

        $this->assertDatabaseMissing('structure_headings', ['id' => $heading->id]);
        $this->assertDatabaseMissing('structure_links', ['id' => $link->id]);
    });
});
