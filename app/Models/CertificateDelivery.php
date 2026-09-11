<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'certificate_id', 'recipient_email', 'status', 'provider_message_id',
    'retry_count', 'max_retries', 'last_attempted_at', 'sent_at', 'failure_reason',
])]
class CertificateDelivery extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatus::class,
            'last_attempted_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(CertificateDeliveryAttempt::class);
    }
}