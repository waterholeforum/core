<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('Assets extenders', function () {
    test('use development asset sources locally with a production fallback', function () {
        $assets = new class extends Extend\Assets\Script {
            public function source(): string
            {
                return $this->sourceDirectory();
            }
        };

        $development = dirname(__DIR__, 3) . '/resources/dist-dev';
        $created = !is_dir($development);
        $environment = app()->environment();

        if ($created) {
            mkdir($development);
        }

        try {
            app()->detectEnvironment(fn() => 'local');
            $local = $assets->source();

            app()->detectEnvironment(fn() => 'production');
            $production = $assets->source();
        } finally {
            app()->detectEnvironment(fn() => $environment);

            if ($created) {
                rmdir($development);
            }
        }

        expect($local)
            ->toBe($development)
            ->and($production)
            ->toBe(dirname(__DIR__, 3) . '/resources/dist');
    });

    test('add stylesheet', function () {
        Storage::fake('public');
        config(['app.debug' => true]);

        $tempFile = tempnam(sys_get_temp_dir(), 'wh');
        file_put_contents($tempFile, 'body{}');

        extend(function (Extend\Assets\Stylesheet $styles) use ($tempFile) {
            $styles->add($tempFile);
        });

        $urls = app(Extend\Assets\Stylesheet::class)->urls(['default']);

        $file = Storage::disk('public')->get(explode('/storage/', $urls[0])[1]);
        expect($file)->toContain('body{}');

        @unlink($tempFile);
    });

    test('add script', function () {
        Storage::fake('public');
        config(['app.debug' => true]);

        $tempFile = tempnam(sys_get_temp_dir(), 'wh');
        file_put_contents($tempFile, 'console.log("test");');

        extend(function (Extend\Assets\Script $scripts) use ($tempFile) {
            $scripts->add($tempFile);
        });

        $urls = app(Extend\Assets\Script::class)->urls(['default']);

        $file = Storage::disk('public')->get(explode('/storage/', $urls[0])[1]);
        expect($file)->toContain('console.log("test");');

        @unlink($tempFile);
    });

    test('add locale', function () {
        Channel::factory()->public()->create();

        extend(function (Extend\Assets\Locales $locales) {
            $locales->add('French', 'fr');
        });

        $this->get('/')->assertSeeText('French');
    });
});
