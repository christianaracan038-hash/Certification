<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports')->restrictOnDelete();
            $table->string('name', 255);
            $table->string('email', 255); // converted to citext below on Postgres
            $table->date('event_date')->nullable();
            $table->jsonb('extra_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['import_id', 'email']);
            $table->index('email');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE participants ALTER COLUMN email TYPE citext');

            DB::statement(
                'CREATE INDEX participants_name_trgm_idx
                 ON participants USING gin (name gin_trgm_ops)'
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};