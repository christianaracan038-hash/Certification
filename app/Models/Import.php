<?php

namespace App\Models;

use App\Enums\ImportStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'original_filename', 'storage_disk', 'storage_path', 'column_mapping',
    'status', 'total_rows', 'valid_rows', 'invalid_rows', 'duplicate_rows',
    'imported_by', 'confirmed_at',
])]
class Import extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'column_mapping' => 'array',
            'status' => ImportStatus::class,
            'confirmed_at' => 'datetime',
        ];
    }

    public function importedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function importRows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function certificateBatches(): HasMany
    {
        return $this->hasMany(CertificateBatch::class);
    }
}