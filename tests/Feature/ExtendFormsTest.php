<?php

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Validator;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\ReactionSet;
use Waterhole\Models\Taxonomy;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
    ExtendTestPersistField::reset();
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

    test('field callbacks run on registration submit', function () {
        app()->extend(Extend\Forms\RegistrationForm::class, function ($extender) {
            $extender->add(ExtendTestPersistField::class, 'extend-test');

            return $extender;
        });

        $this->get(URL::route('waterhole.register'))->assertSeeText(ExtendTestPersistField::MARKER);

        $this->post(URL::route('waterhole.register.submit'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('extend_test_headline');

        $this->post(URL::route('waterhole.register.submit'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'extend_test_headline' => 'Extended headline',
        ])->assertRedirect(URL::route('waterhole.home'));

        $user = User::where('email', 'test@example.com')->firstOrFail();

        expect($user->headline)->toBe('Extended headline');
        expect(ExtendTestPersistField::$savedCalled)->toBeTrue();
        expect(ExtendTestPersistField::$savedUserId)->toBe($user->id);
    });
});

dataset('form_extenders', [
    'channel form' => [
        Extend\Forms\ChannelForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.channels.create')),
    ],
    'page form' => [
        Extend\Forms\PageForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.pages.create')),
    ],
    'structure link form' => [
        Extend\Forms\StructureLinkForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.structure.links.create')),
    ],
    'taxonomy form' => [
        Extend\Forms\TaxonomyForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.taxonomies.create')),
    ],
    'tag form' => [
        Extend\Forms\TagForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        function ($test) {
            $taxonomy = Taxonomy::create(['name' => 'Test Taxonomy']);

            return $test
                ->actingAs(extendTestAdminUser())
                ->get(URL::route('waterhole.cp.taxonomies.tags.create', $taxonomy));
        },
    ],
    'group form' => [
        Extend\Forms\GroupForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.groups.create')),
    ],
    'user form' => [
        Extend\Forms\UserForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.users.create')),
    ],
    'reaction set form' => [
        Extend\Forms\ReactionSetForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test
            ->actingAs(extendTestAdminUser())
            ->get(URL::route('waterhole.cp.reaction-sets.create')),
    ],
    'reaction type form' => [
        Extend\Forms\ReactionTypeForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        function ($test) {
            $reactionSet = ReactionSet::create(['name' => 'Test Reaction Set']);

            return $test->actingAs(extendTestAdminUser())->get(
                URL::route('waterhole.cp.reaction-sets.reaction-types.create', [
                    'reactionSet' => $reactionSet,
                ]),
            );
        },
    ],
    'registration form' => [
        Extend\Forms\RegistrationForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        fn($test) => $test->get(URL::route('waterhole.register')),
    ],
    'post form' => [
        Extend\Forms\PostForm::class,
        fn($extender, $marker) => $extender->add(ExtendTestRenderField::class, 'extend-test'),
        function ($test) {
            $channel = Channel::factory()
                ->public()
                ->create();

            return $test
                ->actingAs(extendTestAdminUser())
                ->get(URL::route('waterhole.posts.create', ['channel_id' => $channel->id]));
        },
    ],
]);

class ExtendTestRenderField extends Field
{
    public const MARKER = 'Extend Test Field';

    public function __construct(public ?Model $model)
    {
    }

    public function render(): string
    {
        return '<div>' . self::MARKER . '</div>';
    }
}

class ExtendTestPersistField extends Field
{
    public const MARKER = 'Extend Test Headline';

    public static bool $savedCalled = false;
    public static ?int $savedUserId = null;

    public static function reset(): void
    {
        self::$savedCalled = false;
        self::$savedUserId = null;
    }

    public function __construct(public ?User $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field name="extend_test_headline" label="Extend Test Headline">
                <input
                    type="text"
                    name="extend_test_headline"
                    id="{{ $component->id }}"
                    value="{{ old('extend_test_headline', $model->headline ?? '') }}"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'extend_test_headline' => ['required', 'string', 'max:255'],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->headline = $request->validated('extend_test_headline');
    }

    public function saved(FormRequest $request): void
    {
        self::$savedCalled = true;
        self::$savedUserId = $this->model->id;
    }
}
