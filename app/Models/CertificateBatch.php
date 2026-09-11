<?php

namespace App\Models;

use App\Enums\CertificateBatchStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'import_id', 'template_version_id', 'created_by', 'configuration', 'status',
    'total_count', 'generated_count', 'failed_count', 'started_at', 'completed_at',
])]
class CertificateBatch extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'configuration' => 'array',
            'status' => CertificateBatchStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function templateVersion(): BelongsTo
    {
        return $this->belongsTo(TemplateVersion::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}