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
            if (!Schema::hasColumn('vehicle_bookings', 'pickup_location')) {
                $table->string('pickup_location')->nullable()->after('destination');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'pickup_lat')) {
                $table->decimal('pickup_lat', 10, 8)->nullable()->after('pickup_location');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'pickup_lng')) {
                $table->decimal('pickup_lng', 11, 8)->nullable()->after('pickup_lat');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'destination_lat')) {
                $table->decimal('destination_lat', 10, 8)->nullable()->after('pickup_lng');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'destination_lng')) {
                $table->decimal('destination_lng', 11, 8)->nullable()->after('destination_lat');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'distance_km')) {
                $table->decimal('distance_km', 8, 2)->nullable()->after('destination_lng');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'estimated_duration_minutes')) {
                $table->integer('estimated_duration_minutes')->nullable()->after('distance_km');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'trip_mode')) {
                $table->string('trip_mode')->default('incidental')->after('estimated_duration_minutes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            $cols = [
                'pickup_location',
                'pickup_lat',
                'pickup_lng',
                'destination_lat',
                'destination_lng',
                'distance_km',
                'estimated_duration_minutes',
                'trip_mode'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('vehicle_bookings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
