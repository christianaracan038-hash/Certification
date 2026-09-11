<?php

namespace Database\Factories;

use App\Enums\TemplateStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TemplateFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true).' Certificate';

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'status' => TemplateStatus::Active,
            'created_by' => User::factory(),
        ];
    }
}