<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['template_id', 'version_number', 'storage_disk', 'storage_path', 'is_active'])]
class TemplateVersion extends Model
{
    use HasFactory;

    // Table has only `created_at`, no `updated_at`.
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function certificateBatches(): HasMany
    {
        return $this->hasMany(CertificateBatch::class);
    }
}