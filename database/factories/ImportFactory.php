<?php

namespace Database\Factories;

use App\Enums\ImportStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'original_filename' => fake()->word().'.xlsx',
            'storage_disk' => 'local',
            'storage_path' => 'imports/'.fake()->uuid().'.xlsx',
            'column_mapping' => ['Full Name' => 'name', 'Email' => 'email', 'Event Date' => 'event_date'],
            'status' => ImportStatus::Confirmed,
            'total_rows' => 0,
            'valid_rows' => 0,
            'invalid_rows' => 0,
            'duplicate_rows' => 0,
            'imported_by' => User::factory(),
            'confirmed_at' => now(),
        ];
    }
}