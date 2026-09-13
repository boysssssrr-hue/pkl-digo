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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');          // Nama alat tulis (misal: Buku Tulis Sinar Dunia)
        $table->text('description')->nullable(); // Deskripsi barang
        $table->decimal('price', 10, 2); // Harga barang
        $table->integer('stock');        // Jumlah stok
        $table->string('image')->nullable();     // Foto produk
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
