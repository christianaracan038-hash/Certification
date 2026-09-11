<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_version_id')
                ->constrained('template_versions')->cascadeOnDelete();
            $table->string('field_key', 100);
            $table->string('label', 255);
            $table->enum('data_type', ['string', 'date', 'number']);
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->unique(['template_version_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_fields');
    }
};