<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'certificate_id' => Certificate::factory(),
            'storage_disk' => 'local',
            'storage_path' => 'certificates/'.fake()->uuid().'.pdf',
            'file_name' => 'certificate.pdf',
            'file_size' => fake()->numberBetween(80_000, 400_000),
            'mime_type' => 'application/pdf',
            'checksum' => hash('sha256', fake()->uuid()),
            'is_current' => true,
        ];
    }
}