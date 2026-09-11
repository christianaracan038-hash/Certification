<?php

namespace Database\Factories;

use App\Enums\ValidationStatus;
use App\Models\Import;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportRowFactory extends Factory
{
    public function definition(): array
    {
        return [
            'import_id' => Import::factory(),
            'row_number' => fake()->numberBetween(1, 500),
            'raw_data' => ['Full Name' => fake()->name(), 'Email' => fake()->safeEmail()],
            'mapped_data' => null,
            'validation_status' => ValidationStatus::Valid,
            'validation_errors' => null,
            'participant_id' => null,
        ];
    }
}