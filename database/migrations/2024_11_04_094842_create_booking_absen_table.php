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
        Schema::create('booking_absen', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('kode_booking')->index('kode_booking');
            $table->string('nama_event')->index('nama_event');
            $table->string('nama_ruangan')->index('ruangan');
            $table->string('lantai')->index('lantai');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('user_name')->nullable()->index('nama_cekin');
            $table->string('nama_pic')->index('nama_pic');
            $table->string('phone')->nullable()->index('phone');
            $table->enum('status', ['Check-in', 'Booked', 'Check-out'])->default('Booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_absen');
    }
};
