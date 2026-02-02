<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Extend;
use Waterhole\Models\Channel;

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
});
