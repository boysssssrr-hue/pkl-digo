<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->integer('capacity')->default(10);
                $table->string('floor')->default('Lantai 1');
                $table->string('facilities')->nullable();
                $table->string('status')->default('Tersedia'); // Tersedia, Sedang Digunakan, Pemeliharaan
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
