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
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->foreign(['kode_booking'], 'peminjaman_barang_booking_id_foreign')->references(['kode_booking'])->on('booking')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['kode_booking'])->references(['kode_booking'])->on('booking')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->dropForeign('peminjaman_barang_booking_id_foreign');
            $table->dropForeign('peminjaman_barang_kode_booking_foreign');
        });
    }
};
