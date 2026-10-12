<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE vehicle_bookings MODIFY vehicle_id BIGINT UNSIGNED NULL");
        } catch (\Throwable $e) {
            Schema::table('vehicle_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('vehicle_id')->nullable()->change();
            });
        }

        try {
            DB::statement("ALTER TABLE vehicle_bookings MODIFY destination TEXT NOT NULL");
        } catch (\Throwable $e) {
            Schema::table('vehicle_bookings', function (Blueprint $table) {
                $table->text('destination')->change();
            });
        }

        if (!Schema::hasColumn('vehicle_bookings', 'purpose')) {
            Schema::table('vehicle_bookings', function (Blueprint $table) {
                $table->text('purpose')->nullable()->after('destination');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vehicle_bookings', 'purpose')) {
            Schema::table('vehicle_bookings', function (Blueprint $table) {
                $table->dropColumn('purpose');
            });
        }
    }
};
