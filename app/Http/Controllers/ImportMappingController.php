<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateImportMappingRequest;
use App\Models\Import;
use App\Services\ImportColumnDetectionService;
use App\Services\ImportMappingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ImportMappingController extends Controller
{
    public function __construct(
        protected ImportColumnDetectionService $columnDetectionService,
        protected ImportMappingService $mappingService,
    ) {}

    public function edit(Import $import): Response
    {
        return Inertia::render('imports/mapping', [
            'import' => $import->only(['id', 'original_filename', 'status']),
            'detectedColumns' => $this->columnDetectionService->detectHeadings($import),
            'targetFields' => [
                ['value' => 'name', 'label' => 'Name', 'required' => true],
                ['value' => 'email', 'label' => 'Email', 'required' => true],
                ['value' => 'event_date', 'label' => 'Event Date', 'required' => false],
            ],
        ]);
    }

    public function update(UpdateImportMappingRequest $request, Import $import): RedirectResponse
    {
        $this->mappingService->saveMapping($import, $request->validated('mapping'));

        // Placeholder: once Slice 3 (validation) exists, this becomes
        // redirect()->route('imports.validate', $import).
        return redirect()->route('imports.create')
            ->with('success', 'Column mapping saved. Validation step comes next.');
    }
}