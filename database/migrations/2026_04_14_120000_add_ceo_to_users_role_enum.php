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
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('manager', 'marketing', 'documentation', 'admin', 'ceo')
            NOT NULL DEFAULT 'marketing'
        ");
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

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('manager', 'marketing', 'documentation', 'admin')
            NOT NULL DEFAULT 'marketing'
        ");
    }
};
