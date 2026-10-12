<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'status_key')) {
                $table->string('status_key')->default('available')->after('status'); // 'available', 'departed', 'returning'
            }
            if (!Schema::hasColumn('vehicles', 'current_driver_id')) {
                $table->foreignId('current_driver_id')->nullable()->after('status_key')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('vehicles', 'current_driver_name')) {
                $table->string('current_driver_name')->nullable()->after('current_driver_id');
            }
            if (!Schema::hasColumn('vehicles', 'current_passengers')) {
                $table->string('current_passengers')->nullable()->after('current_driver_name');
            }
            if (!Schema::hasColumn('vehicles', 'current_division')) {
                $table->string('current_division')->nullable()->after('current_passengers');
            }
            if (!Schema::hasColumn('vehicles', 'current_destination')) {
                $table->string('current_destination')->nullable()->after('current_division');
            }
            if (!Schema::hasColumn('vehicles', 'color')) {
                $table->string('color')->default('#10b981')->after('current_destination');
            }
            if (!Schema::hasColumn('vehicles', 'body_color')) {
                $table->string('body_color')->default('#047857')->after('color');
            }
            if (!Schema::hasColumn('vehicles', 'trip_started_at')) {
                $table->dateTime('trip_started_at')->nullable()->after('body_color');
            }
            if (!Schema::hasColumn('vehicles', 'trip_returned_at')) {
                $table->dateTime('trip_returned_at')->nullable()->after('trip_started_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['current_driver_id']);
            $table->dropColumn([
                'status_key',
                'current_driver_id',
                'current_driver_name',
                'current_passengers',
                'current_division',
                'current_destination',
                'color',
                'body_color',
                'trip_started_at',
                'trip_returned_at',
            ]);
        });
    }
};
