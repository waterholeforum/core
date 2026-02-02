<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/structure', function () {
    test('list structure', function () {
        Channel::factory()->public()->create();

        Page::factory()->public()->create();

        $response = jsonApi('GET', '/api/structure?include=content');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonCount(2, 'included');
    });
});

describe('api/structure headings and links', function () {
    test('retrieve structure heading', function () {
        $heading = StructureHeading::create(['name' => 'Heading']);

        $response = jsonApi('GET', "/api/structureHeadings/$heading->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'structureHeadings',
                'id' => $heading->id,
            ],
        ]);
    });

    test('retrieve structure link', function () {
        $link = StructureLink::create([
            'name' => 'Waterhole',
            'href' => 'https://waterhole.dev',
        ]);

        $link->savePermissions(['group:1' => ['view' => true]]);

        $response = jsonApi('GET', "/api/structureLinks/$link->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'structureLinks',
                'id' => $link->id,
            ],
        ]);
    });
});
