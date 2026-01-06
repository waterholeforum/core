<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Waterhole\Database\Seeders\GroupsSeeder;
use Waterhole\Models\Channel;
use Waterhole\Models\User;
use Waterhole\Waterhole;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(GroupsSeeder::class);
});

test('guests are denied gated abilities when the forum is private', function () {
    config(['waterhole.forum.public' => false]);

    $channel = Channel::factory()
        ->public()
        ->create();

    $user = User::factory()->create();

    expect(Waterhole::permissions()->can(null, 'view', $channel))->toBeFalse();
    expect(Waterhole::permissions()->can($user, 'view', $channel))->toBeTrue();

    expect(Waterhole::permissions()->ids(null, 'view', Channel::class))->toBeEmpty();
    expect(Waterhole::permissions()->ids($user, 'view', Channel::class))->toEqual([$channel->id]);

    expect(Gate::forUser(null)->allows('waterhole.channel.view', $channel))->toBeFalse();
});
