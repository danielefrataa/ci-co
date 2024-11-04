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
<<<<<<< Updated upstream
        Schema::create('absen', function (Blueprint $table){
            $table->increments('id')->primary();
=======
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Menambahkan kolom name
            $table->string('phone'); // Menambahkan kolom phone
>>>>>>> Stashed changes
            $table->integer('id_booking');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen'); // Hapus tabel saat rollback migrasi
    }
};
