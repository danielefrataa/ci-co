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
        Schema::create('peminjaman_barang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_booking', 11)->index('peminjaman_barang_booking_id_foreign');
            $table->string('nama_item');
            $table->unsignedInteger('jumlah');
            $table->string('lokasi');
            $table->string('marketing')->nullable();
            $table->string('FO')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang');
    }
};
