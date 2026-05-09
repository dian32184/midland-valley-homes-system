<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->date('approved_at')->nullable()->after('paid_at');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payrolls MODIFY COLUMN status ENUM('pending','processed','paid','approved') NOT NULL DEFAULT 'pending'");
            DB::table('payrolls')->whereIn('status', ['paid', 'processed'])->update([
                'status' => 'approved',
                'approved_at' => DB::raw('COALESCE(paid_at, CURDATE())'),
            ]);
        } else {
            $today = now()->toDateString();
            DB::table('payrolls')->whereIn('status', ['paid', 'processed'])->update([
                'status' => 'approved',
                'approved_at' => $today,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::table('payrolls')->where('status', 'approved')->update(['status' => 'paid']);
            DB::statement("ALTER TABLE payrolls MODIFY COLUMN status ENUM('pending','processed','paid') NOT NULL DEFAULT 'pending'");
        }
    }
};
