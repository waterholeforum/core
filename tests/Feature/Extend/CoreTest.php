<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Filters\Filter;
use Waterhole\Layouts\Layout;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Post;
use Waterhole\Models\User;
use Waterhole\Notifications\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

class ExtendTestPostLayout extends Layout
{
    public function label(): string
    {
        return 'Extend Test Layout';
    }

    public function itemComponent(): string
    {
        return 'waterhole::post-list-item';
    }

    public function wrapperClass(): string
    {
        return 'extend-test-layout';
    }
}

class ExtendTestPostFilter extends Filter
{
    public const TITLE = 'Extend Test Visible';

    public function handle(): string
    {
        return 'extend-test-filter';
    }

    public function label(): string
    {
        return 'Extend Test Filter';
    }

    public function apply(Builder $query): void
    {
        $query->where('posts.title', self::TITLE);
    }
}

class ExtendTestNotification extends Notification
{
    public static function description(): ?string
    {
        return 'Extend Test Notification';
    }

    public function title(): string
    {
        return 'Extend Test Notification';
    }
}

describe('Core extenders', function () {
    test('add post layout', function () {
        extend(function (Extend\Core\PostLayouts $layouts) {
            $layouts->add(ExtendTestPostLayout::class);
        });

        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)
            ->get(URL::route('waterhole.cp.structure.channels.create'))
            ->assertSeeText('Extend Test Layout');

        $channel = Channel::factory()
            ->public()
            ->create(['layout' => ExtendTestPostLayout::class]);

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Layout Post']);

        $this->get(URL::route('waterhole.channels.show', $channel))->assertSee(
            'extend-test-layout',
        );
    });

    test('add post filter', function () {
        extend(function (Extend\Core\PostFilters $filters) {
            $filters->add(ExtendTestPostFilter::class);
        });

        $admin = User::factory()->create();
        $admin->groups()->attach(Group::ADMIN_ID);

        $this->actingAs($admin)
            ->get(URL::route('waterhole.cp.structure.channels.create'))
            ->assertSeeText('Extend Test Filter');

        $channel = Channel::factory()
            ->public()
            ->create(['filters' => [ExtendTestPostFilter::class]]);

        Post::factory()
            ->for($channel)
            ->create(['title' => ExtendTestPostFilter::TITLE]);

        Post::factory()
            ->for($channel)
            ->create(['title' => 'Other Post']);

        $response = $this->get(
            URL::route('waterhole.channels.show', $channel) .
                '?filter=' .
                (new ExtendTestPostFilter())->handle(),
        );

        $response->assertSeeText(ExtendTestPostFilter::TITLE);
        $response->assertDontSeeText('Other Post');
    });

    test('add notification type', function () {
        extend(function (Extend\Core\NotificationTypes $types) {
            $types->add(ExtendTestNotification::class, 'extend-test');
        });

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(URL::route('waterhole.preferences.notifications'))
            ->assertSeeText('Extend Test Notification');
    });

    test('add formatter hooks', function () {
        $configured = false;
        $parsed = false;
        $rendered = false;

        extend(function (Extend\Core\Formatter $formatter) use (
            &$configured,
            &$parsed,
            &$rendered,
        ) {
            $formatter->configure(function () use (&$configured) {
                $configured = true;
            });

            $formatter->parsing(function ($parser, string &$text) use (&$parsed) {
                $parsed = true;
                $text = '**Parsed**';
            });

            $formatter->rendering(function ($renderer, string &$xml) use (&$rendered) {
                $rendered = true;
                $xml = '<r><B>Rendered</B></r>';
            });
        });

        app(Extend\Core\Formatter::class);

        $formatter = app(\Waterhole\Formatter\Formatter::class);
        $formatter->flush();

        $xml = $formatter->parse('Hello world');
        $html = $formatter->render($xml);

        expect($configured)->toBeTrue();
        expect($parsed)->toBeTrue();
        expect($rendered)->toBeTrue();
        expect($xml)->toContain('Parsed');
        expect($html)->toContain('Rendered');
    });
});
