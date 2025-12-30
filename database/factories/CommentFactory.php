<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\Comment;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return ['body' => fake()->text];
    }
}
