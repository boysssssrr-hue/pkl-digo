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
            if (!Schema::hasColumn('vehicle_bookings', 'borrower_name')) {
                $table->string('borrower_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'borrower_nip')) {
                $table->string('borrower_nip')->nullable()->after('borrower_name');
            }
            if (!Schema::hasColumn('vehicle_bookings', 'borrower_division')) {
                $table->string('borrower_division')->nullable()->after('borrower_nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_bookings', 'borrower_division')) {
                $table->dropColumn('borrower_division');
            }
            if (Schema::hasColumn('vehicle_bookings', 'borrower_nip')) {
                $table->dropColumn('borrower_nip');
            }
            if (Schema::hasColumn('vehicle_bookings', 'borrower_name')) {
                $table->dropColumn('borrower_name');
            }
        });
    }
};
