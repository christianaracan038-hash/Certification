<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// 'certificate_number' deliberately NOT fillable — generated exclusively
// by CertificateNumberCounter logic, never mass-assigned.
#[Fillable([
    'certificate_batch_id', 'participant_id', 'template_version_id',
    'status', 'rendered_data', 'error_message', 'generated_at',
])]
class Certificate extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => CertificateStatus::class,
            'rendered_data' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function certificateBatch(): BelongsTo
    {
        return $this->belongsTo(CertificateBatch::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function templateVersion(): BelongsTo
    {
        return $this->belongsTo(TemplateVersion::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(CertificateFile::class);
    }

    public function currentFile(): HasOne
    {
        return $this->hasOne(CertificateFile::class)->where('is_current', true);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(CertificateDelivery::class);
    }
}