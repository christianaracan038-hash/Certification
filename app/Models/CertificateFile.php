<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'certificate_id', 'storage_disk', 'storage_path', 'file_name',
    'file_size', 'mime_type', 'checksum', 'is_current',
])]
class CertificateFile extends Model
{
    use HasFactory;

    // Table has only `created_at`, no `updated_at`.
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_current' => 'boolean',
        ];
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}