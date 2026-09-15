<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithLimit;

class HeadingRowImport implements ToCollection, WithLimit
{
    public array $headings = [];

    public function collection($rows): void
    {
        $this->headings = $rows->first()?->toArray() ?? [];
    }

    public function limit(): int
    {
        return 1;
    }
}