<?php

namespace App\Services;

use App\Enums\ImportStatus;
use App\Models\Import;

class ImportMappingService
{
    public function saveMapping(Import $import, array $mapping): Import
    {
        $import->update([
            'column_mapping' => array_filter($mapping),
            'status' => ImportStatus::Mapped,
        ]);

        return $import;
    }
}