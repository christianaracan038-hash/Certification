<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports')->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->jsonb('raw_data');
            $table->jsonb('mapped_data')->nullable();
            $table->enum('validation_status', ['valid', 'invalid', 'duplicate', 'warning']);
            $table->jsonb('validation_errors')->nullable();
            $table->foreignId('participant_id')->nullable()
                ->constrained('participants')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['import_id', 'validation_status']);
            $table->index('participant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_rows');
    }
};