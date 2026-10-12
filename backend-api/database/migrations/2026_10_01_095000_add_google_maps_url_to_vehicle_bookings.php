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
            if (!Schema::hasColumn('vehicle_bookings', 'google_maps_url')) {
                $table->text('google_maps_url')->nullable()->after('estimated_duration_minutes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_bookings', 'google_maps_url')) {
                $table->dropColumn('google_maps_url');
            }
        });
    }
};
