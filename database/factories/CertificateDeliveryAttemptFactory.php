<?php

namespace Database\Factories;

use App\Enums\DeliveryAttemptStatus;
use App\Models\CertificateDelivery;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateDeliveryAttemptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'certificate_delivery_id' => CertificateDelivery::factory(),
            'attempt_number' => 1,
            'status' => DeliveryAttemptStatus::Sent,
            'provider_message_id' => fake()->uuid(),
            'attempted_at' => now(),
        ];
    }
}