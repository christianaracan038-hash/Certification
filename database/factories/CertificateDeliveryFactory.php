<?php

namespace Database\Factories;

use App\Enums\DeliveryStatus;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateDeliveryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'certificate_id' => Certificate::factory(),
            'recipient_email' => fake()->safeEmail(),
            'status' => DeliveryStatus::Sent,
            'provider_message_id' => fake()->uuid(),
            'retry_count' => 0,
            'max_retries' => 3,
            'last_attempted_at' => now(),
            'sent_at' => now(),
            'failure_reason' => null,
        ];
    }
}