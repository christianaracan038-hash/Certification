<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_delivery_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_delivery_id')
                ->constrained('certificate_deliveries')->cascadeOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->enum('status', ['sent', 'failed']);
            $table->string('provider_message_id', 255)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('attempted_at')->useCurrent();

            $table->index('certificate_delivery_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_delivery_attempts');
    }
};