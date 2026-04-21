<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // SQLite doesn't support native ENUM types (and typically stores this as TEXT),
        // so there's nothing to alter at the database level.
        if ($driver === 'sqlite') {
            return;
        }

        // MySQL supports altering an ENUM column definition directly.
        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY COLUMN role ENUM('manager', 'marketing', 'documentation', 'admin', 'ceo')
                NOT NULL DEFAULT 'marketing'
            ");

            return;
        }

        throw new RuntimeException("Unsupported database driver for role enum migration: {$driver}");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            UPDATE users
            SET role = 'marketing'
            WHERE role = 'ceo'
        ");

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE users
                MODIFY COLUMN role ENUM('manager', 'marketing', 'documentation', 'admin')
                NOT NULL DEFAULT 'marketing'
            ");

            return;
        }

        throw new RuntimeException("Unsupported database driver for role enum migration: {$driver}");
    }
};
