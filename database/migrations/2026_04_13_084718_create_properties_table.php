<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('block_number');
            $table->string('lot_number');
            $table->string('house_type');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('lot_size')->nullable();
            $table->string('floor_area')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold', 'under_construction', 'turned_over'])->default('available');
            $table->text('description')->nullable();
            $table->date('available_at')->nullable();
            $table->timestamps();
            $table->unique(['block_number', 'lot_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
