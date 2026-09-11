<?php

namespace App\Models;

use App\Enums\ValidationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'import_id', 'row_number', 'raw_data', 'mapped_data',
    'validation_status', 'validation_errors', 'participant_id',
])]
class ImportRow extends Model
{
    use HasFactory;

    // Table has only `created_at`, no `updated_at`.
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'raw_data' => 'array',
            'mapped_data' => 'array',
            'validation_status' => ValidationStatus::class,
            'validation_errors' => 'array',
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}