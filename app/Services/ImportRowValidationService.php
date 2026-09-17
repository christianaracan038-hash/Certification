<?php

namespace App\Services;

use App\Enums\ImportStatus;
use App\Enums\ValidationStatus;
use App\Imports\ParticipantRowsImport;
use App\Models\Import;
use App\Models\ImportRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportRowValidationService
{
    public function validate(Import $import): void
    {
        DB::transaction(function () use ($import) {
            $import->importRows()->delete();
            $reader = new ParticipantRowsImport();
            Excel::import($reader, $import->storage_path, $import->storage_disk);

            $mapping = $import->column_mapping ?? [];
            $seenEmails = [];
            $valid = $invalid = $duplicate = 0;

            foreach ($reader->dataRows as $index => $rowValues) {
                $raw = array_combine(
                    $reader->headings,
                    array_pad($rowValues, count($reader->headings), null),
                );

                $mapped = $this->applyMapping($raw, $mapping);

                $name = trim((string) ($mapped['name'] ?? ''));
                $email = trim((string) ($mapped['email'] ?? ''));
                $rawDate = $mapped['event_date'] ?? null;

                $errors = [];

                if ($name === '') {
                    $errors[] = 'Name is required.';
                }

                if ($email === '') {
                    $errors[] = 'Email is required.';
                } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Email format is invalid.';
                }

                $parsedDate = null;
                if (filled($rawDate)) {
                    try {
                        $parsedDate = Carbon::parse($rawDate)->toDateString();
                    } catch (\Throwable) {
                        $errors[] = 'Event date could not be understood.';
                    }
                }

                $normalizedEmail = strtolower($email);
                $status = ValidationStatus::Valid;

                if ($errors !== []) {
                    $status = ValidationStatus::Invalid;
                    $invalid++;
                } elseif ($normalizedEmail !== '' && isset($seenEmails[$normalizedEmail])) {
                    $status = ValidationStatus::Duplicate;
                    $errors[] = "Duplicate email within this file (also row {$seenEmails[$normalizedEmail]}).";
                    $duplicate++;
                } else {
                    $valid++;
                }

                if ($normalizedEmail !== '' && $status !== ValidationStatus::Invalid) {
                    $seenEmails[$normalizedEmail] ??= $index + 2; // +2: zero-indexed + header row
                }

                ImportRow::create([
                    'import_id' => $import->id,
                    'row_number' => $index + 2,
                    'raw_data' => $raw,
                    'mapped_data' => [
                        'name' => $name ?: null,
                        'email' => $email ?: null,
                        'event_date' => $parsedDate,
                    ],
                    'validation_status' => $status,
                    'validation_errors' => $errors === [] ? null : $errors,
                ]);
            }

            $import->update([
                'total_rows' => count($reader->dataRows),
                'valid_rows' => $valid,
                'invalid_rows' => $invalid,
                'duplicate_rows' => $duplicate,
                'status' => ImportStatus::Validated,
            ]);
        });
    }

    private function applyMapping(array $raw, array $mapping): array
    {
        $mapped = [];

        foreach ($mapping as $sourceColumn => $targetField) {
            $mapped[$targetField] = $raw[$sourceColumn] ?? null;
        }

        return $mapped;
    }
}