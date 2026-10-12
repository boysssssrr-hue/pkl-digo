<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('division_item_limits')) return;

        Schema::create('division_item_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity_limit')->default(1);
            $table->string('period')->default('monthly');
            $table->timestamps();
            $table->unique(['division_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('division_item_limits');
    }
};