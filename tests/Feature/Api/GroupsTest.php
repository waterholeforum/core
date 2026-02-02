<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Group;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/groups', function () {
    test('list groups', function () {
        $response = jsonApi('GET', '/api/groups');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    });

    test('retrieve group', function () {
        $group = Group::custom()->firstOrFail();

        $response = jsonApi('GET', "/api/groups/$group->id");

        $response->assertOk();
        $response->assertJson(['data' => ['type' => 'groups', 'id' => $group->id]]);
    });
});
