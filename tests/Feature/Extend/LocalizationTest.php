<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;
use Waterhole\Translation\FluentTranslator;

uses(RefreshDatabase::class);

describe('locales', function () {
    test('lists available locales including extensions', function () {
        $this->seed(GroupsSeeder::class);

        Channel::factory()->public()->create();

        extend(function (Extend\Assets\Locales $locales) {
            $locales->add('French', 'fr');
            $locales->add('Pirate', 'pirate');
        });

        $this->get('/')->assertOk()->assertSeeText('French')->assertSeeText('Pirate');
    });

    test('preserves BCP 47 locale identifiers during browser language negotiation', function (string $locale) {
        $this->seed(GroupsSeeder::class);

        Channel::factory()->public()->create();

        extend(function (Extend\Assets\Locales $locales) use ($locale) {
            $locales->add($locale, $locale);
        });

        $files = new Filesystem();
        $path = storage_path('framework/testing/' . uniqid('locale-', true));
        $directory = "$path/$locale";

        try {
            $files->ensureDirectoryExists($directory);
            $files->put("$directory/forum.ftl", 'greeting = Hello from Fluent');
            $files->put(
                "$directory/messages.php",
                "<?php\n\nreturn ['greeting' => 'Hello from PHP'];\n",
            );
            app(FluentTranslator::class)->addNamespace('locale-test', $path);

            $this
                ->withHeader('Accept-Language', $locale)
                ->get('/')
                ->assertOk()
                ->assertSee('lang="' . $locale . '"', false);

            expect(app()->getLocale())
                ->toBe($locale)
                ->and(__('locale-test::forum.greeting'))
                ->toBe('Hello from Fluent')
                ->and(__('locale-test::messages.greeting'))
                ->toBe('Hello from PHP');
        } finally {
            $files->deleteDirectory($path);
        }
    })->with(['zh-Hans', 'zh-Hant', 'pt-BR']);

    test('refreshes cached translations when source files change', function () {
        $files = new Filesystem();
        $path = storage_path('framework/testing/' . uniqid('fluent-', true));
        $file = "$path/lang/en/forum.ftl";
        $cachePath = "$path/cache";

        try {
            $files->ensureDirectoryExists(dirname($file));
            $files->put($file, 'greeting = Hello');

            $translator = fn() => new FluentTranslator(
                new Translator(new ArrayLoader(), 'en'),
                $files,
                "$path/lang",
                'en',
                'en',
                [],
                $cachePath,
            );

            expect($translator()->get('forum.greeting'))->toBe('Hello');

            $files->put($file, 'greeting = G’day');
            touch($file, time() + 1);

            expect($translator()->get('forum.greeting'))->toBe('G’day');
        } finally {
            $files->deleteDirectory($path);
        }
    });
});

test('replaces placeholders in missing translation keys', function () {
    expect(app(FluentTranslator::class)->get('Click the :actionText button', [
        'actionText' => 'Renew License',
    ]))
        ->toBe('Click the Renew License button');
});

test('does not use fallback locale when fallback is disabled', function () {
    $translator = app(FluentTranslator::class);
    $translator->addLines(['messages.fallback-only' => 'Fallback'], 'en');

    expect($translator->get('messages.fallback-only', [], 'fr'))
        ->toBe('Fallback')
        ->and($translator->get('messages.fallback-only', [], 'fr', false))
        ->toBe('messages.fallback-only')
        ->and($translator->hasForLocale('messages.fallback-only', 'fr'))
        ->toBeFalse();
});
