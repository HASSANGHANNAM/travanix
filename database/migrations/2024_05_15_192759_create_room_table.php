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
            $table->integer('size_room');
            $table->string('size_of_bed');
            $table->integer('capacity');
            $table->integer('price');
            $table->string('available_services');
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
