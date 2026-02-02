<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

describe('api/channels', function () {
    test('retrieve channel', function () {
        $channel = Channel::factory()->public()->create();

        $response = jsonApi('GET', "/api/channels/$channel->id");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'type' => 'channels',
                'id' => $channel->id,
                'attributes' => ['name' => $channel->name, 'url' => $channel->url],
            ],
        ]);
    });

    test('retrieve channel user state', function () {
        $this->actingAs(User::factory()->create());

        $channel = Channel::factory()->public()->create();

        $response = jsonApi('GET', "/api/channels/$channel->id?include=userState");

        $response->assertOk();
        $response->assertJson([
            'included' => [['type' => 'channelUsers', 'attributes' => ['notifications' => null]]],
        ]);
    });
});
