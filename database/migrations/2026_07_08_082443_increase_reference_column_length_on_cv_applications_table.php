<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE cv_applications MODIFY COLUMN reference VARCHAR(20)');

            if (! Schema::hasIndex('cv_applications', 'cv_applications_reference_unique')) {
                DB::statement('CREATE UNIQUE INDEX cv_applications_reference_unique ON cv_applications (reference)');
            }
        } elseif ($driver === 'sqlite') {
            // SQLite ignores most ALTER MODIFY; tests use a fresh in-memory DB so this is a no-op.
        } else {
            // Postgres etc.
            DB::statement('ALTER TABLE cv_applications ALTER COLUMN reference TYPE VARCHAR(20)');
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE cv_applications MODIFY COLUMN reference VARCHAR(12)');
        } elseif ($driver === 'sqlite') {
            // no-op
        } else {
            DB::statement('ALTER TABLE cv_applications ALTER COLUMN reference TYPE VARCHAR(12)');
        }
    }
};
