<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS citext');
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        }
    }

    public function down(): void
    {
        // Not dropping extensions on rollback: dependent objects (citext
        // columns, trigram indexes) rely on them, and dropping shared
        // extensions is destructive. Roll back dependent migrations first.
    }
};