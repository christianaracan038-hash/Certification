<?php

namespace App\Services;

use App\Imports\HeadingRowImport;
use App\Models\Import;
use Maatwebsite\Excel\Facades\Excel;

class ImportColumnDetectionService
{
    public function detectHeadings(Import $import): array
    {
        $reader = new HeadingRowImport();

        Excel::import($reader, $import->storage_path, $import->storage_disk);

        return array_values(array_filter(
            $reader->headings,
            fn ($heading) => filled($heading),
        ));
    }
}