<?php

namespace App\Models;

use App\Enums\TemplateFieldDataType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['template_version_id', 'field_key', 'label', 'data_type', 'is_required', 'sort_order'])]
class TemplateField extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'data_type' => TemplateFieldDataType::class,
            'is_required' => 'boolean',
        ];
    }

    public function templateVersion(): BelongsTo
    {
        return $this->belongsTo(TemplateVersion::class);
    }
}