<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_versions', function (Blueprint $table) {
            $table->id();
            // Cascade is safe: certificates reference template_versions with
            // RESTRICT, so Postgres blocks deleting any version that has
            // ever issued a certificate — cascade only fires for unused versions.
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('storage_disk', 50);
            $table->string('storage_path', 500);
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['template_id', 'version_number']);
            $table->index(['template_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_versions');
    }
};