<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;

class ParticipantRowsImport implements ToCollection
{
    public array $headings = [];

    public array $dataRows = [];

    public function collection($rows): void
    {
        $rows = $rows
            ->filter(fn ($row) => $row->filter(fn ($value) => filled($value))->isNotEmpty())
            ->values();

        $this->headings = $rows->first()?->toArray() ?? [];
        $this->dataRows = $rows->slice(1)->values()->map(fn ($row) => $row->toArray())->all();
    }
}