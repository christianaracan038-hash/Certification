<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateVersionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'template_id' => Template::factory(),
            'version_number' => 1,
            'storage_disk' => 'local',
            'storage_path' => 'templates/'.fake()->uuid().'.html',
            'is_active' => true,
        ];
    }
}