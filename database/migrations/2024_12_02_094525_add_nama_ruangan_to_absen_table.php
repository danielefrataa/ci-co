<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('absen', function (Blueprint $table) {
            $table->string('nama_ruangan')->nullable();  // Menambahkan kolom nama_ruangan
        });
    }
    
    public function down()
    {
        Schema::table('absen', function (Blueprint $table) {
            $table->dropColumn('nama_ruangan');
        });
    }
    
};
