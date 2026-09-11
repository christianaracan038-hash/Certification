<?php

namespace Database\Factories;

use App\Enums\TemplateFieldDataType;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFieldFactory extends Factory
{
    public function definition(): array
    {
        return [
            'template_version_id' => TemplateVersion::factory(),
            'field_key' => fake()->randomElement(['name', 'event_date', 'certificate_number']),
            'label' => fake()->words(2, true),
            'data_type' => TemplateFieldDataType::String,
            'is_required' => true,
            'sort_order' => 0,
        ];
    }
}