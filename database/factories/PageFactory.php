<?php

namespace Waterhole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Waterhole\Models\Page;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return ['name' => fake()->name, 'body' => fake()->text];
    }

    public function public(): static
    {
        return $this->afterCreating(function (Page $page) {
            $page->savePermissions(['group:1' => ['view' => true]]);
        });
    }
}
