<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();
            $table->string('storage_disk', 50);
            $table->string('storage_path', 500);
            $table->string('file_name', 255);
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 100);
            $table->string('checksum', 64)->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->index('certificate_id');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement(
                'CREATE UNIQUE INDEX certificate_files_current_unique
                 ON certificate_files (certificate_id) WHERE is_current = true'
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_files');
    }
};