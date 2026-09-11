<?php

namespace Database\Factories;

use App\Enums\CertificateStatus;
use App\Models\CertificateBatch;
use App\Models\Participant;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    // certificate_number is deliberately not set here by default — the
    // seeder assigns it sequentially, matching real numbering behavior.
    // If you call this factory standalone, override it explicitly.
    public function definition(): array
    {
        return [
            'certificate_batch_id' => CertificateBatch::factory(),
            'participant_id' => Participant::factory(),
            'template_version_id' => TemplateVersion::factory(),
            'certificate_number' => 'CERT-'.now()->year.'-'.fake()->unique()->numberBetween(100000, 999999),
            'status' => CertificateStatus::Generated,
            'rendered_data' => ['name' => fake()->name(), 'event_date' => now()->toDateString()],
            'generated_at' => now(),
        ];
    }
}