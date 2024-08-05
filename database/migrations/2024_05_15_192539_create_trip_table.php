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
        Schema::create('trip', function (Blueprint $table) {
            $table->id();
            $table->string('trip_name', 45)->unique();
            $table->double('price_trip')->unsigned();
            $table->integer('number_of_allSeat')->unsigned();
            $table->string('type_of_trip', 45);
            $table->text('description');
            $table->dateTime('trip_start_time');
            $table->dateTime('trip_end_time');
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip');
    }
};
