<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return ['body' => fake()->text];
    }
}
