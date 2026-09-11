<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['import_id', 'name', 'email', 'event_date', 'extra_data'])]
class Participant extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'extra_data' => 'array',
        ];
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function importRows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}