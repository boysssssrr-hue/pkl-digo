<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Room Bookings (Peminjaman Ruangan)
        if (!Schema::hasTable('room_bookings')) {
            Schema::create('room_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->string('agenda');
                $table->dateTime('start_time')->nullable();
                $table->dateTime('end_time')->nullable();
                $table->string('status')->default('pending'); // pending, approved, completed, rejected
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Vehicle Bookings (Peminjaman Mobil)
        if (!Schema::hasTable('vehicle_bookings')) {
            Schema::create('vehicle_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->string('destination');
                $table->dateTime('start_date')->nullable();
                $table->dateTime('end_date')->nullable();
                $table->integer('passengers_count')->default(1);
                $table->string('status')->default('pending'); // pending, approved, completed, rejected
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Cleaning Service Requests (Layanan Kebersihan)
        if (!Schema::hasTable('cleaning_requests')) {
            Schema::create('cleaning_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('cleaning_tool_id')->nullable()->constrained('cleaning_tools')->nullOnDelete();
                $table->string('service_type');
                $table->string('location');
                $table->dateTime('requested_date')->nullable();
                $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_requests');
        Schema::dropIfExists('vehicle_bookings');
        Schema::dropIfExists('room_bookings');
    }
};
