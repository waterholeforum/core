<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->userName,
            'email' => fake()->email,
            'email_verified_at' => now(),
        ];
    }
}
