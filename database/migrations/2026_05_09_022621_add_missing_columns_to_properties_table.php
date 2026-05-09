<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('properties', function (Blueprint $table) {
        $table->enum('lot_type', ['regular', 'corner_lot'])->default('regular')->after('house_model');
    });
}

public function down(): void
{
    Schema::table('properties', function (Blueprint $table) {
        $table->dropColumn('lot_type');
    });
}
};