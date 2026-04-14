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
        Schema::dropIfExists('loan_applications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider')->default('pagibig');
            $table->string('application_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'released', 'rejected'])->default('pending');
            $table->date('applied_at')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('released_at')->nullable();
            $table->date('rejected_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
};
