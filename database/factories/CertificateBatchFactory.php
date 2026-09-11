<?php

namespace Database\Factories;

use App\Enums\CertificateBatchStatus;
use App\Models\Import;
use App\Models\TemplateVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateBatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'import_id' => Import::factory(),
            'template_version_id' => TemplateVersion::factory(),
            'created_by' => User::factory(),
            'configuration' => ['font' => 'Georgia', 'layout' => 'landscape'],
            'status' => CertificateBatchStatus::Completed,
            'total_count' => 0,
            'generated_count' => 0,
            'failed_count' => 0,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ];
    }
}