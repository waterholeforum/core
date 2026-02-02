<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Waterhole\Actions\Action;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Page;
use Waterhole\Models\Post;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\ReactionType;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;
use Waterhole\Models\Tag;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

class ExtendTestAction extends Action
{
    public function authorize(?User $user, \Waterhole\Models\Model $model): bool
    {
        return true;
    }

    public function label(\Illuminate\Support\Collection $models): string
    {
        return 'Extend Test Action';
    }
}

describe('Actions extenders', function () {
    test('add action', function (string $actionable, callable $makeModel) {
        extend(function (Extend\Core\Actions $actions) use ($actionable) {
            $actions->for($actionable)->add(ExtendTestAction::class, 'extend-test');
        });

        $model = $makeModel();

        $this->get(
            URL::route('waterhole.actions.menu', [
                'actionable' => $actionable,
                'id' => $model->id,
            ]),
        )->assertSeeText('Extend Test Action');

        if ($actionable === Post::class) {
            $this->get(
                URL::route('waterhole.actions.create', [
                    'actionable' => $actionable,
                    'id' => $model->id,
                    'action_class' => ExtendTestAction::class,
                ]),
            )->assertOk();

            $this->post(URL::route('waterhole.actions.store'), [
                'actionable' => $actionable,
                'id' => $model->id,
                'action_class' => ExtendTestAction::class,
            ]);
        }
    })->with('actionables');
});

dataset('actionables', [
    'channel' => [Channel::class, fn() => Channel::factory()->public()->create()],
    'comment' => [
        Comment::class,
        fn() => Comment::factory()
            ->for(Post::factory()->for(Channel::factory()->public()))
            ->create(),
    ],
    'group' => [
        \Waterhole\Models\Group::class,
        fn() => \Waterhole\Models\Group::create(['name' => 'Test Group', 'is_public' => true]),
    ],
    'page' => [Page::class, fn() => Page::factory()->public()->create()],
    'post' => [
        Post::class,
        fn() => Post::factory()
            ->for(Channel::factory()->public())
            ->create(),
    ],
    'reactionSet' => [
        ReactionSet::class,
        fn() => ReactionSet::create(['name' => 'Test Reaction Set']),
    ],
    'reactionType' => [
        ReactionType::class,
        fn() => ReactionType::create([
            'reaction_set_id' => ReactionSet::create(['name' => 'Test Reaction Set'])->id,
            'name' => 'Test Reaction Type',
            'score' => 1,
            'position' => 0,
        ]),
    ],
    'structureHeading' => [
        StructureHeading::class,
        fn() => StructureHeading::create(['name' => 'Test Heading']),
    ],
    'structureLink' => [
        StructureLink::class,
        fn() => tap(
            StructureLink::create(['name' => 'Test Link', 'href' => 'https://example.com']),
            fn($link) => $link->savePermissions(['group:1' => ['view' => true]]),
        ),
    ],
    'tag' => [
        Tag::class,
        fn() => Tag::create([
            'taxonomy_id' => tap(
                Taxonomy::create(['name' => 'Test Taxonomy']),
                fn($taxonomy) => $taxonomy->savePermissions(['group:1' => ['view' => true]]),
            )->id,
            'name' => 'Test Tag',
        ]),
    ],
    'taxonomy' => [
        Taxonomy::class,
        fn() => tap(
            Taxonomy::create(['name' => 'Test Taxonomy']),
            fn($taxonomy) => $taxonomy->savePermissions(['group:1' => ['view' => true]]),
        ),
    ],
    'user' => [User::class, fn() => User::factory()->create()],
]);
