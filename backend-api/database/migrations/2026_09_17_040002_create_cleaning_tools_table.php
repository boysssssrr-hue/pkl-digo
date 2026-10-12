<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cleaning_tools')) {
            Schema::create('cleaning_tools', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->string('category')->default('Mesin Pembersih');
                $table->integer('quantity')->default(1);
                $table->string('unit')->default('Unit');
                $table->string('location')->default('Gudang Janitor Lt. 1');
                $table->string('status')->default('Tersedia'); // Tersedia, Sedang Digunakan, Perbaikan
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_tools');
    }
};
