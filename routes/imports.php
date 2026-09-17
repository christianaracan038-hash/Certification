<?php

use App\Http\Controllers\ImportController;
use App\Http\Controllers\ImportMappingController;
use App\Http\Controllers\ImportValidationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('imports/create', [ImportController::class, 'create'])->name('imports.create');
    Route::post('imports', [ImportController::class, 'store'])->name('imports.store');

    Route::get('imports/{import}/mapping',[ImportMappingController::class, 'edit'])->name('imports.mapping.edit');
    Route::put('imports/{import}/mapping',[ImportMappingController::class, 'update'])->name('imports.mapping.update');

      Route::get('imports/{import}/validate', [ImportValidationController::class, 'create'])->name('imports.validate.create');
    Route::post('imports/{import}/validate', [ImportValidationController::class, 'store'])->name('imports.validate.store');


});