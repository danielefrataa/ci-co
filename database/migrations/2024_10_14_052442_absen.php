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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 11)->index(); // Kode booking dengan indeks
            $table->date('tanggal')->nullable(); // Tanggal
            $table->time('jam')->nullable(); // Jam
            $table->string('nama_event')->nullable(); // Nama event
            $table->string('kategori_event')->nullable(); // Kategori event
            $table->string('kategori_ekraf')->nullable(); // Kategori ekraf
            $table->string('ruangan')->nullable(); // Ruangan
            $table->text('deskripsi')->nullable(); // Deskripsi
            $table->unsignedInteger('jumlah_peserta'); // Jumlah peserta
            $table->string('nama_pic')->nullable(); // Nama PIC
            $table->string('no_pic')->nullable(); // Nomor PIC
            $table->string('jenis_event')->nullable(); // Jenis event
            $table->string('proposal')->nullable(); // Proposal
            $table->string('banner')->nullable(); // Banner
            $table->string('status')->nullable(); // Status
            $table->timestamps(); // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking'); // Hapus tabel saat rollback migrasi
    }
};
