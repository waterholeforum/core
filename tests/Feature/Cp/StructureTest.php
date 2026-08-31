<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Waterhole\Actions\DeleteChannel;
use Waterhole\Actions\DeleteStructure;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Filters\Answered;
use Waterhole\Filters\Newest;
use Waterhole\Filters\Oldest;
use Waterhole\Filters\Top;
use Waterhole\Filters\Unanswered;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
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

describe('structure hierarchy', function () {
    test('create a child from a structure node', function () {
        $parent = Page::factory()->public()->create();

        $this
            ->actingAs(cpStructureAdmin())
            ->post(
                route('waterhole.cp.structure.pages.store', [
                    'parent_id' => $parent->structure->id,
                ]),
                [
                    'name' => 'Child Page',
                    'slug' => 'child-page',
                    'icon' => ['type' => null],
                    'body' => 'Child body',
                ],
            )
            ->assertRedirect(route('waterhole.cp.structure'));

        expect(Page::where('slug', 'child-page')->firstOrFail()->structure->parent_id)
            ->toBe($parent->structure->id);
    });

    test('save a nested structure', function () {
        $page = Page::factory()->public()->create(['name' => 'Parent']);
        $channel = Channel::factory()->public()->create(['name' => 'Child']);

        $this
            ->actingAs(cpStructureAdmin())
            ->post(route('waterhole.cp.structure'), [
                'order' => json_encode([
                    ['id' => $page->structure->id, 'parent_id' => null, 'position' => 0],
                    [
                        'id' => $channel->structure->id,
                        'parent_id' => $page->structure->id,
                        'position' => 0,
                    ],
                ]),
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        expect($channel->structure->fresh()->parent_id)->toBe($page->structure->id);
    });

    test('preserve sibling order when promoting children', function () {
        $page = Page::factory()->public()->create();
        $firstChild = Page::factory()->public()->create();
        $secondChild = Page::factory()->public()->create();
        $after = Page::factory()->public()->create();

        $firstChild->structure->update(['parent_id' => $page->structure->id, 'position' => 0]);
        $secondChild->structure->update(['parent_id' => $page->structure->id, 'position' => 1]);

        $this->actingAs(cpStructureAdmin())->post(route('waterhole.actions.store'), [
            'actionable' => Page::class,
            'id' => $page->id,
            'action_class' => DeleteStructure::class,
            'confirmed' => true,
        ])->assertRedirect();

        expect(
            Structure::withoutGlobalScopes()
                ->whereNull('parent_id')
                ->inSiblingOrder()
                ->pluck('id')
                ->all(),
        )->toBe([
            $firstChild->structure->id,
            $secondChild->structure->id,
            $after->structure->id,
        ]);
    });

    test('reject invalid structure parents and cycles', function () {
        $page = Page::factory()->public()->create();
        $child = Page::factory()->public()->create();
        $channel = Channel::factory()->public()->create();
        $heading = StructureHeading::create(['name' => 'Heading']);

        expect(fn() => $child->structure->update(['parent_id' => $heading->structure->id]))
            ->toThrow(ValidationException::class);

        $channel->structure->update(['parent_id' => $page->structure->id]);
        $child->structure->update(['parent_id' => $channel->structure->id]);

        expect(fn() => $page->structure->update(['parent_id' => $child->structure->id]))
            ->toThrow(ValidationException::class);
    });

    test('show the active structure path on channel pages', function () {
        $page = Page::factory()->public()->create(['name' => 'Parent']);
        $channel = Channel::factory()->public()->create(['name' => 'Child']);
        $channel->structure->update(['parent_id' => $page->structure->id]);

        $this->get($channel->url)->assertOk()->assertSeeInOrder(['Parent', 'Child']);
    });
});

describe('cp channels', function () {
    test('create channel', function () {
        $this
            ->actingAs(cpStructureAdmin())
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
        $channel = Channel::factory()->public()->create(['name' => 'Old', 'slug' => 'old']);

        $this
            ->actingAs(cpStructureAdmin())
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

    test('show answer filters only for persisted answer-enabled channels', function () {
        $admin = cpStructureAdmin();
        $disabled = Channel::factory()->public()->create();
        $enabled = Channel::factory()->public()->create(['answerable' => true]);

        $this
            ->actingAs($admin)
            ->get(route('waterhole.cp.structure.channels.create'))
            ->assertOk()
            ->assertDontSeeText('Unanswered')
            ->assertDontSeeText('Answered');

        $this
            ->get(route('waterhole.cp.structure.channels.edit', $disabled))
            ->assertOk()
            ->assertDontSeeText('Unanswered')
            ->assertDontSeeText('Answered');

        $this
            ->get(route('waterhole.cp.structure.channels.edit', $enabled))
            ->assertOk()
            ->assertSeeText('Unanswered')
            ->assertSeeText('Answered');
    });

    test('retain dormant answer filters while editing visible filters', function () {
        $channel = Channel::factory()
            ->public()
            ->create([
                'answerable' => false,
                'filters' => [
                    Newest::class,
                    Unanswered::class,
                    Top::class,
                    Answered::class,
                    Oldest::class,
                ],
            ]);

        $this
            ->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.channels.update', $channel), [
                'name' => $channel->name,
                'slug' => $channel->slug,
                'icon' => ['type' => null],
                'ignore' => 0,
                'answerable' => 1,
                'show_similar_posts' => 0,
                'require_approval_posts' => 0,
                'require_approval_comments' => 0,
                'custom_filters' => 1,
                'filters' => [Oldest::class, Newest::class],
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        expect($channel->fresh()->filters)->toBe([
            Oldest::class,
            Unanswered::class,
            Newest::class,
            Answered::class,
        ]);

        $this
            ->get(route('waterhole.cp.structure.channels.edit', $channel))
            ->assertOk()
            ->assertSeeInOrder(['Oldest', 'Unanswered', 'Newest', 'Answered']);
    });

    test('delete channel', function () {
        $channel = Channel::factory()->public()->create();
        $node = $channel->structure;

        $this->actingAs(cpStructureAdmin())->post(route('waterhole.actions.store'), [
            'actionable' => Channel::class,
            'id' => $channel->id,
            'action_class' => DeleteChannel::class,
            'confirmed' => true,
            'move_posts' => false,
        ])->assertRedirect();

        $this->assertDatabaseMissing('channels', ['id' => $channel->id]);
        $this->assertDatabaseMissing('permissions', [
            'scope_type' => $node->getMorphClass(),
            'scope_id' => $node->id,
        ]);
    });
});

describe('cp pages', function () {
    test('create page', function () {
        $this
            ->actingAs(cpStructureAdmin())
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
        $page = Page::factory()->public()->create(['name' => 'Old', 'slug' => 'old']);

        $this
            ->actingAs(cpStructureAdmin())
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

        $this->actingAs(cpStructureAdmin())->post(route('waterhole.actions.store'), [
            'actionable' => Page::class,
            'id' => $page->id,
            'action_class' => DeleteStructure::class,
            'confirmed' => true,
        ])->assertRedirect();

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    });
});

describe('cp structure links and headings', function () {
    test('create structure heading', function () {
        $this->actingAs(cpStructureAdmin())->post(route('waterhole.cp.structure.headings.store'), [
            'name' => 'Heading',
        ])->assertRedirect(route('waterhole.cp.structure'));

        $this->assertDatabaseHas('structure_headings', ['name' => 'Heading']);
    });

    test('create structure link', function () {
        $this
            ->actingAs(cpStructureAdmin())
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

        $this
            ->actingAs(cpStructureAdmin())
            ->put(route('waterhole.cp.structure.headings.update', $heading), [
                'name' => 'New Heading',
            ])
            ->assertRedirect(route('waterhole.cp.structure'));

        $this
            ->actingAs(cpStructureAdmin())
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
