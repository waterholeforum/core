<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\URL;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

function extendTestAdminUser(): User
{
    $admin = User::factory()->create();
    $admin->groups()->attach(Group::ADMIN_ID);

    return $admin;
}

describe('Form extenders', function () {
    test('add form field', function (
        string $extenderClass,
        callable $extendField,
        callable $makeRequest,
    ) {
        $marker = 'Extend Test Field';

        app()->extend($extenderClass, function ($extender) use ($extendField, $marker) {
            $extendField($extender, $marker);

            return $extender;
        });

        $response = $makeRequest($this);

        $response->assertSeeText($marker);
    })->with('form_extenders');
});

dataset('form_extenders', [
    'channel form' => [
        Extend\Forms\ChannelForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.channels.create')),
    ],
    'page form' => [
        Extend\Forms\PageForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.pages.create')),
    ],
    'structure link form' => [
        Extend\Forms\StructureLinkForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.links.create')),
    ],
    'taxonomy form' => [
        Extend\Forms\TaxonomyForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.taxonomies.create')),
    ],
    'tag form' => [
        Extend\Forms\TagForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        function ($test) {
            $taxonomy = Taxonomy::create(['name' => 'Test Taxonomy']);

            return $test->actingAs(extendTestAdminUser())
                ->get(URL::route('waterhole.cp.taxonomies.tags.create', $taxonomy));
        },
    ],
    'group form' => [
        Extend\Forms\GroupForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.groups.create')),
    ],
    'user form' => [
        Extend\Forms\UserForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.users.create')),
    ],
    'reaction set form' => [
        Extend\Forms\ReactionSetForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.reaction-sets.create')),
    ],
    'reaction type form' => [
        Extend\Forms\ReactionTypeForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        function ($test) {
            $reactionSet = ReactionSet::create(['name' => 'Test Reaction Set']);

            return $test->actingAs(extendTestAdminUser())
                ->get(
                    URL::route('waterhole.cp.reaction-sets.reaction-types.create', [
                        'reactionSet' => $reactionSet,
                    ]),
                );
        },
    ],
    'registration form' => [
        Extend\Forms\RegistrationForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        fn($test) => $test->get(URL::route('waterhole.register')),
    ],
    'post form' => [
        Extend\Forms\PostForm::class,
        fn($extender, $marker) => $extender->add('extend-test', new HtmlString($marker)),
        function ($test) {
            $channel = Channel::factory()->public()->create();

            return $test->actingAs(extendTestAdminUser())
                ->get(URL::route('waterhole.posts.create', ['channel_id' => $channel->id]));
        },
    ],
]);
