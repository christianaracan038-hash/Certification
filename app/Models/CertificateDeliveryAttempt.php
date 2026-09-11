<?php

namespace App\Models;

use App\Enums\DeliveryAttemptStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'certificate_delivery_id', 'attempt_number', 'status',
    'provider_message_id', 'error_message', 'attempted_at',
])]
class CertificateDeliveryAttempt extends Model
{
    use HasFactory;

    // Table has only `attempted_at` — no created_at/updated_at at all.
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => DeliveryAttemptStatus::class,
            'attempted_at' => 'datetime',
        ];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(CertificateDelivery::class, 'certificate_delivery_id');
    }
}