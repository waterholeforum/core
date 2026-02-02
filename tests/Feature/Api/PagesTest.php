<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Page;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/pages', function () {
    test('retrieve page', function () {
        $page = Page::factory()->public()->create();

        $response = jsonApi('GET', "/api/pages/$page->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'pages', 'id' => $page->id]]);
    });
});
