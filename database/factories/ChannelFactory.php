<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\Channel;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return ['name' => fake()->name];
    }

    public function public(): static
    {
        return $this->afterCreating(function (Channel $channel) {
            $channel->savePermissions([
                'group:1' => ['view' => true],
                'group:2' => ['view' => true, 'post' => true, 'comment' => true],
            ]);
        });
    }

    public function readOnly(): static
    {
        return $this->afterCreating(function (Channel $channel) {
            $channel->savePermissions(['group:1' => ['view' => true]]);
        });
    }
}
