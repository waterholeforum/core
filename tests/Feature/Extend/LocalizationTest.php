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

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('locales', function () {
    test('lists available locales including extensions', function () {
        Channel::factory()->public()->create();

        extend(function (Extend\Assets\Locales $locales) {
            $locales->add('French', 'fr');
            $locales->add('Pirate', 'pirate');
        });

        $this->get('/')->assertOk()->assertSeeText('French')->assertSeeText('Pirate');
    });

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
