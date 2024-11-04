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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_ruangan')->index('nama_ruangan_2');
            $table->string('kapasitas');
            $table->string('ukuran')->nullable();
            $table->string('biaya_sewa')->nullable()->default('GRATIS');
            $table->text('detail_ruangan')->nullable();
            $table->string('pic');
            $table->string('lantai')->index('lantai');
            $table->string('fasilitas');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
