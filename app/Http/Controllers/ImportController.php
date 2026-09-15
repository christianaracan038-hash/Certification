<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportRequest;
use App\Services\ImportUploadService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ImportController extends Controller
{
    public function __construct(
        protected ImportUploadService $importUploadService,
    ) {}

    public function create(): Response
    {
        return Inertia::render('imports/create');
    }

    public function store(StoreImportRequest $request): RedirectResponse
    {
        $import = $this->importUploadService->store($request->file('file'));

        // Placeholder redirect — once Slice 2 (column mapping) exists,
        // this becomes redirect()->route('imports.mapping', $import).
        return redirect()->route('imports.create')
            ->with('success', "\"{$import->original_filename}\" uploaded. Column mapping comes next.");
    }
}