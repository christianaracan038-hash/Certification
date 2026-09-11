<?php

namespace Database\Factories;

use App\Models\Import;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'import_id' => Import::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'event_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'extra_data' => null,
        ];
    }
}