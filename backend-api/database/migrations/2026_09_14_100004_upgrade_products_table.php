<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'barcode')) $table->string('barcode')->nullable()->unique();
            if (!Schema::hasColumn('products', 'category')) $table->string('category')->default('Alat Tulis');
            if (!Schema::hasColumn('products', 'unit')) $table->string('unit')->default('Pcs');
            if (!Schema::hasColumn('products', 'status')) $table->string('status')->default('available');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['barcode', 'category', 'unit', 'status'] as $column) {
                if (Schema::hasColumn('products', $column)) $table->dropColumn($column);
            }
        });
    }
};
