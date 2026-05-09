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
            $table->string('spouse_first_name')->nullable()->after('pagibig_mid_number');
            $table->string('spouse_middle_name')->nullable()->after('spouse_first_name');
            $table->string('spouse_last_name')->nullable()->after('spouse_middle_name');

            $table->string('present_address_street')->nullable()->after('phone');
            $table->string('present_address_barangay')->nullable()->after('present_address_street');
            $table->string('present_address_city')->nullable()->after('present_address_barangay');
            $table->string('present_address_province')->nullable()->after('present_address_city');
            $table->string('present_address_zip', 20)->nullable()->after('present_address_province');
        });

        foreach (DB::table('customers')->whereNotNull('address')->cursor() as $row) {
            if (! empty($row->present_address_street)) {
                continue;
            }
            DB::table('customers')->where('id', $row->id)->update([
                'present_address_street' => $row->address,
            ]);
        }

        foreach (DB::table('customers')->whereNotNull('spouse_name')->cursor() as $row) {
            if (! empty($row->spouse_first_name)) {
                continue;
            }
            DB::table('customers')->where('id', $row->id)->update([
                'spouse_first_name' => $row->spouse_name,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'spouse_first_name',
                'spouse_middle_name',
                'spouse_last_name',
                'present_address_street',
                'present_address_barangay',
                'present_address_city',
                'present_address_province',
                'present_address_zip',
            ]);
        });
    }
};
