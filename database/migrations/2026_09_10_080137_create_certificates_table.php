<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_batch_id')
                ->constrained('certificate_batches')->restrictOnDelete();
            $table->foreignId('participant_id')
                ->constrained('participants')->restrictOnDelete();
            $table->foreignId('template_version_id')
                ->constrained('template_versions')->restrictOnDelete();
            $table->string('certificate_number', 30)->unique();
            $table->enum('status', ['pending', 'generating', 'generated', 'failed'])
                ->default('pending');
            $table->jsonb('rendered_data')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['certificate_batch_id', 'participant_id']);
            $table->index('participant_id');
            $table->index('template_version_id');
            $table->index('status');

            // Approved composite index #1: dashboard "recent certificates by status"
            $table->index(['status', 'created_at']);
            // Approved composite index #2: certificates generated but not yet emailed
            $table->index(['status', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};