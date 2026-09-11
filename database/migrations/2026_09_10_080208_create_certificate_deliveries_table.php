<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')
                ->unique()
                ->constrained('certificates')->cascadeOnDelete();
            $table->string('recipient_email', 255); // converted to citext below
            $table->enum('status', ['pending', 'queued', 'sending', 'sent', 'failed', 'bounced'])
                ->default('pending');
            $table->string('provider_message_id', 255)->nullable();
            $table->unsignedInteger('retry_count')->default(0);
            $table->unsignedInteger('max_retries')->default(3);
            $table->timestamp('last_attempted_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE certificate_deliveries ALTER COLUMN recipient_email TYPE citext');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_deliveries');
    }
};