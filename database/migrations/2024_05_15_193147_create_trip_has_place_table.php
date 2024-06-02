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
        Schema::create('trip_has_place', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->foreign('trip_id')->references('id')->on('trip');
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotel');
            $table->unsignedBigInteger('resturant_id')->nullable();
            $table->foreign('resturant_id')->references('id')->on('resturant');
            $table->unsignedBigInteger('attraction_activite_id')->nullable();
            $table->foreign('attraction_activite_id')->references('id')->on('attraction_activities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_has_blace');
    }
};
