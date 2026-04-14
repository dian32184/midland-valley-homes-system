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
        Schema::create('title_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'submitted_to_bir', 'car_issued', 'registered', 'tct_issued', 'rejected'])->default('pending');
            $table->date('submitted_to_bir_at')->nullable();
            $table->date('car_issued_at')->nullable();
            $table->date('registered_at')->nullable();
            $table->date('tct_issued_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_transfers');
    }
};
