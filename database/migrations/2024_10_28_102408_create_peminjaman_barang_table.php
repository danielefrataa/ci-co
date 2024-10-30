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
            $table->id();
            $table->string('kode_booking', 11); // Menggunakan kode_booking sebagai foreign key
            $table->string('nama_item');
            $table->unsignedInteger('jumlah'); // Gunakan unsignedInteger untuk jumlah
            $table->string('lokasi');
            $table->string('marketing')->nullable(); // Mengizinkan nilai NULL
            $table->string('FO')->nullable(); // Mengizinkan nilai NULL
            $table->timestamps();

            // Mendefinisikan foreign key
            $table->foreign('kode_booking')->references('kode_booking')->on('booking')->onDelete('cascade');
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
