<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename', 255);
            $table->string('storage_disk', 50);
            $table->string('storage_path', 500);
            $table->jsonb('column_mapping')->nullable();
            $table->enum('status', ['uploaded', 'mapped', 'validated', 'confirmed', 'failed'])
                ->default('uploaded');
            $table->unsignedInteger('total_rows')->nullable();
            $table->unsignedInteger('valid_rows')->nullable();
            $table->unsignedInteger('invalid_rows')->nullable();
            $table->unsignedInteger('duplicate_rows')->nullable();
            $table->foreignId('imported_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('imported_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};