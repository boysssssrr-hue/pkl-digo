<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('license_plate')->unique();
                $table->string('name');
                $table->string('brand')->default('Toyota');
                $table->string('transmission')->default('Automatic'); // Automatic, Manual
                $table->integer('capacity')->default(7);
                $table->string('fuel_type')->default('Bensin'); // Bensin, Diesel, Listrik
                $table->string('status')->default('Tersedia'); // Tersedia, Sedang Dipakai, Servis Berkala
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
