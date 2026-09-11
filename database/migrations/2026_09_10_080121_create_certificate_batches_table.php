<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->nullable()
                ->constrained('imports')->nullOnDelete();
            $table->foreignId('template_version_id')
                ->constrained('template_versions')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->jsonb('configuration');
            $table->enum('status', [
                'pending', 'processing', 'completed', 'completed_with_errors', 'failed',
            ])->default('pending');
            $table->unsignedInteger('total_count')->default(0);
            $table->unsignedInteger('generated_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('import_id');
            $table->index('template_version_id');
            $table->index('created_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_batches');
    }
};