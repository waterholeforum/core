<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return ['name' => fake()->name];
    }
}
