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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('employment_status', ['employed', 'self_employed', 'unemployed', 'contractual', 'retired'])->default('employed');
            $table->string('employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->enum('status', ['pending', 'for_approval', 'approved', 'rejected'])->default('pending');
            $table->text('profile_notes')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
