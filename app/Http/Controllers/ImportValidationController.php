<?php

namespace App\Http\Controllers;

use App\Enums\ImportStatus;
use App\Models\Import;
use App\Services\ImportRowValidationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ImportValidationController extends Controller
{
    public function __construct(
        protected ImportRowValidationService $validationService,
    ) {}

    public function create(Import $import): Response|RedirectResponse
    {
        if ($import->status === ImportStatus::Uploaded) {
            return redirect()->route('imports.mapping.edit', $import)
                ->with('error', 'Please map the columns before validating.');
        }

        return Inertia::render('imports/validate', [
            'import' => $import->only(['id', 'original_filename', 'status', 'total_rows', 'valid_rows', 'invalid_rows', 'duplicate_rows']),
        ]);
    }

    public function store(Import $import): RedirectResponse
    {
        $this->validationService->validate($import);

        // Placeholder: once Slice 4 (review UI) exists, this becomes
        // redirect()->route('imports.review', $import).
        return redirect()->route('imports.validate.create', $import)
            ->with('success', 'Validation complete.');
    }
}