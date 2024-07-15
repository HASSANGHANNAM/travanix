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
        Schema::create('avg_rate', function (Blueprint $table) {
            $table->id();
            $table->float('avg')->unsigned();
            $table->integer('count')->unsigned();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotel');
            $table->unsignedBigInteger('attraction_activity_id')->nullable();
            $table->foreign('attraction_activity_id')->references('id')->on('attraction_activities');
            $table->unsignedBigInteger('resturant_id')->nullable();
            $table->foreign('resturant_id')->references('id')->on('resturant');
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->foreign('trip_id')->references('id')->on('trip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avg_rate');
    }
};
