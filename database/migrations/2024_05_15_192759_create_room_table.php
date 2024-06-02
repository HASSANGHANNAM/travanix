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
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->integer('size_room')->unsigned();
            $table->string('size_of_bed')->nullable();
            $table->integer('capacity_room')->unsigned();
            $table->float('price_room')->unsigned();
            $table->dateTime('start_reservation')->nullable();
            $table->dateTime('end_reservation')->nullable();
            $table->string('available_services', 255)->nullable();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')->references('id')->on('hotel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room');
    }
};
