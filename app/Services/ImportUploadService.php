<?php

namespace App\Services;

use App\Enums\ImportStatus;
use App\Models\Import;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImportUploadService
{
    public function store(UploadedFile $file): Import
    {
        $disk = 'local';
        $extension = $file->getClientOriginalExtension();

        // Safe generated storage name — never trust the uploaded filename,
        // per Section 18.3. Original name is kept separately for display only.
        $path = $file->storeAs('imports', Str::uuid().'.'.$extension, $disk);

        return Import::create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_disk' => $disk,
            'storage_path' => $path,
            'status' => ImportStatus::Uploaded,
            'imported_by' => Auth::id(),
        ]);
    }
}