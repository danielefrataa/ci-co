<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique(); // Booking code
            $table->string('nama_event'); // Event name
            $table->string('ruangan'); // Room location
            $table->string('waktu'); // Event time
            $table->string('user_name'); // User's name who booked
            $table->enum('status', ['Check-in', 'Booked', 'Check-out'])->default('Booked'); // Booking status
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}