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
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_bookings', 'booking_type')) {
                $table->string('booking_type')->default('incidental')->after('status'); // incidental, routine
            }
            if (!Schema::hasColumn('vehicle_bookings', 'is_routine')) {
                $table->boolean('is_routine')->default(false)->after('booking_type');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'routine_schedule')) {
                $table->string('routine_schedule')->nullable()->after('is_routine');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'recurring_frequency')) {
                $table->string('recurring_frequency')->nullable()->after('routine_schedule'); // daily, weekly, monthly
            }
            if (!Schema::hasColumn('vehicle_bookings', 'recurring_days')) {
                $table->string('recurring_days')->nullable()->after('recurring_frequency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_bookings', 'recurring_days')) {
                $table->dropColumn('recurring_days');
            }
            if (Schema::hasColumn('vehicle_bookings', 'recurring_frequency')) {
                $table->dropColumn('recurring_frequency');
            }
            if (Schema::hasColumn('vehicle_bookings', 'routine_schedule')) {
                $table->dropColumn('routine_schedule');
            }
            if (Schema::hasColumn('vehicle_bookings', 'is_routine')) {
                $table->dropColumn('is_routine');
            }
            if (Schema::hasColumn('vehicle_bookings', 'booking_type')) {
                $table->dropColumn('booking_type');
            }
        });
    }
};
