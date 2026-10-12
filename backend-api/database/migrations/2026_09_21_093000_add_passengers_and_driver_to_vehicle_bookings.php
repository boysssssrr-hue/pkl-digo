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
            if (!Schema::hasColumn('vehicle_bookings', 'passengers')) {
                $table->string('passengers')->nullable()->after('passengers_count');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('vehicle_bookings', 'driver_name')) {
                $table->string('driver_name')->nullable()->after('driver_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_bookings', 'driver_id')) {
                $table->dropForeign(['driver_id']);
                $table->dropColumn(['driver_id', 'driver_name', 'passengers']);
            }
        });
    }
};
