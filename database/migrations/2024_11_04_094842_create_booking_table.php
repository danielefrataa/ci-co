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
            $table->bigIncrements('id');
            $table->string('kode_booking', 11)->index('kode_booking');
            $table->date('tanggal')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai');
            $table->string('nama_event')->index('nama_event');
            $table->string('nama_organisasi');
            $table->string('kategori_event');
            $table->string('kategori_ekraf');
            $table->string('ruangan');
            $table->text('deskripsi');
            $table->timestamps();
            $table->integer('jumlah_peserta');
            $table->string('nama_pic')->index('nama_pic');
            $table->string('no_pic');
            $table->string('jenis_event');
            $table->string('proposal');
            $table->string('banner');
            $table->enum('status', ['Booking', 'Approved', 'Reject'])->default('Booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
