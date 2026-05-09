<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('civil_status')->nullable()->after('date_of_birth');
            $table->string('citizenship')->nullable()->after('civil_status');
            $table->string('tin_number')->nullable()->after('citizenship');
            $table->string('sss_gsis_number')->nullable()->after('tin_number');
            $table->string('pagibig_mid_number')->nullable()->after('sss_gsis_number');
            $table->string('spouse_name')->nullable()->after('pagibig_mid_number');
            $table->string('spouse_employment')->nullable()->after('spouse_name');
            $table->decimal('spouse_monthly_income', 15, 2)->nullable()->after('spouse_employment');
            $table->string('business_name')->nullable()->after('job_title');
            $table->string('business_nature')->nullable()->after('business_name');
            $table->unsignedInteger('years_employed')->nullable()->after('business_nature');
            $table->unsignedInteger('years_in_business')->nullable()->after('years_employed');
            $table->string('ofw_employer_name')->nullable()->after('years_in_business');
            $table->string('ofw_country')->nullable()->after('ofw_employer_name');
            $table->boolean('is_pagibig_member')->default(false)->after('monthly_income');
            $table->boolean('has_required_pagibig_contributions')->default(false)->after('is_pagibig_member');
            $table->boolean('has_outstanding_debts')->default(false)->after('has_required_pagibig_contributions');
            $table->decimal('savings_amount', 15, 2)->nullable()->after('has_outstanding_debts');
            $table->boolean('has_downpayment_capacity')->default(false)->after('savings_amount');
            $table->boolean('has_stable_income')->default(false)->after('has_downpayment_capacity');
            $table->foreignId('selected_property_id')->nullable()->after('status')->constrained('properties')->nullOnDelete();
            $table->string('project_name')->nullable()->after('selected_property_id');
            $table->decimal('contract_price', 15, 2)->nullable()->after('project_name');
            $table->decimal('reservation_fee_amount', 15, 2)->nullable()->after('contract_price');
            $table->decimal('downpayment_amount', 15, 2)->nullable()->after('reservation_fee_amount');
            $table->string('preferred_financing_type')->nullable()->after('downpayment_amount');
            $table->decimal('monthly_amortization_estimate', 15, 2)->nullable()->after('preferred_financing_type');
            $table->text('affordability_notes')->nullable()->after('monthly_amortization_estimate');
            $table->text('qualification_notes')->nullable()->after('affordability_notes');
            $table->text('documentation_notes')->nullable()->after('qualification_notes');
            $table->text('manager_notes')->nullable()->after('documentation_notes');
            $table->string('valid_id_status')->default('pending')->after('manager_notes');
            $table->string('proof_of_billing_status')->default('pending')->after('valid_id_status');
            $table->string('proof_of_income_status')->default('pending')->after('proof_of_billing_status');
            $table->string('birth_certificate_status')->default('pending')->after('proof_of_income_status');
            $table->string('marriage_certificate_status')->default('pending')->after('birth_certificate_status');
            $table->string('reservation_form_status')->default('pending')->after('marriage_certificate_status');
            $table->string('financing_documents_status')->default('pending')->after('reservation_form_status');
        });

        DB::table('customers')
            ->whereNull('project_name')
            ->update(['project_name' => 'Midland Valley Homes']);

        DB::table('customers')
            ->whereNotNull('address')
            ->update(['address' => DB::raw('address')]);

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE customers MODIFY COLUMN employment_status ENUM('employed','self_employed','ofw','unemployed','contractual','retired') NOT NULL DEFAULT 'employed'");
            DB::statement("ALTER TABLE customers MODIFY COLUMN status ENUM('pending','for_approval','approved','rejected','new_applicant','for_profiling','requirements_incomplete','eligible_for_submission','submitted_to_financing','under_review','declined','pending_compliance','released','moved_in') NOT NULL DEFAULT 'new_applicant'");
        }

        DB::table('customers')->where('status', 'pending')->update(['status' => 'new_applicant']);
        DB::table('customers')->where('status', 'for_approval')->update(['status' => 'under_review']);
        DB::table('customers')->where('status', 'rejected')->update(['status' => 'declined']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE customers MODIFY COLUMN status ENUM('new_applicant','for_profiling','requirements_incomplete','eligible_for_submission','submitted_to_financing','under_review','approved','declined','pending_compliance','released','moved_in') NOT NULL DEFAULT 'new_applicant'");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE customers MODIFY COLUMN status ENUM('pending','for_approval','approved','rejected','new_applicant','for_profiling','requirements_incomplete','eligible_for_submission','submitted_to_financing','under_review','declined','pending_compliance','released','moved_in') NOT NULL DEFAULT 'pending'");
        }

        DB::table('customers')->where('status', 'new_applicant')->update(['status' => 'pending']);
        DB::table('customers')->where('status', 'for_profiling')->update(['status' => 'pending']);
        DB::table('customers')->where('status', 'requirements_incomplete')->update(['status' => 'pending']);
        DB::table('customers')->where('status', 'eligible_for_submission')->update(['status' => 'for_approval']);
        DB::table('customers')->where('status', 'submitted_to_financing')->update(['status' => 'for_approval']);
        DB::table('customers')->where('status', 'under_review')->update(['status' => 'for_approval']);
        DB::table('customers')->where('status', 'declined')->update(['status' => 'rejected']);
        DB::table('customers')->where('status', 'pending_compliance')->update(['status' => 'for_approval']);
        DB::table('customers')->where('status', 'released')->update(['status' => 'approved']);
        DB::table('customers')->where('status', 'moved_in')->update(['status' => 'approved']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE customers MODIFY COLUMN status ENUM('pending','for_approval','approved','rejected') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE customers MODIFY COLUMN employment_status ENUM('employed','self_employed','unemployed','contractual','retired') NOT NULL DEFAULT 'employed'");
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['selected_property_id']);
            $table->dropColumn([
                'civil_status',
                'citizenship',
                'tin_number',
                'sss_gsis_number',
                'pagibig_mid_number',
                'spouse_name',
                'spouse_employment',
                'spouse_monthly_income',
                'business_name',
                'business_nature',
                'years_employed',
                'years_in_business',
                'ofw_employer_name',
                'ofw_country',
                'is_pagibig_member',
                'has_required_pagibig_contributions',
                'has_outstanding_debts',
                'savings_amount',
                'has_downpayment_capacity',
                'has_stable_income',
                'selected_property_id',
                'project_name',
                'contract_price',
                'reservation_fee_amount',
                'downpayment_amount',
                'preferred_financing_type',
                'monthly_amortization_estimate',
                'affordability_notes',
                'qualification_notes',
                'documentation_notes',
                'manager_notes',
                'valid_id_status',
                'proof_of_billing_status',
                'proof_of_income_status',
                'birth_certificate_status',
                'marriage_certificate_status',
                'reservation_form_status',
                'financing_documents_status',
            ]);
        });
    }
};
