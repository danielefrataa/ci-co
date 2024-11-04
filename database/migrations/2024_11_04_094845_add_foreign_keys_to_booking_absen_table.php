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
        Schema::table('booking_absen', function (Blueprint $table) {
            $table->foreign(['kode_booking'], 'kode_booking')->references(['kode_booking'])->on('booking')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lantai'], 'lantai')->references(['lantai'])->on('ruangan')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['user_name'], 'nama_cekin')->references(['name'])->on('absen')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['nama_event'], 'nama_event')->references(['nama_event'])->on('booking')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['nama_pic'], 'nama_pic')->references(['nama_pic'])->on('booking')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['phone'], 'phone')->references(['phone'])->on('absen')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['nama_ruangan'], 'ruangan')->references(['nama_ruangan'])->on('ruangan')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_absen', function (Blueprint $table) {
            $table->dropForeign('kode_booking');
            $table->dropForeign('lantai');
            $table->dropForeign('nama_cekin');
            $table->dropForeign('nama_event');
            $table->dropForeign('nama_pic');
            $table->dropForeign('phone');
            $table->dropForeign('ruangan');
        });
    }
};
