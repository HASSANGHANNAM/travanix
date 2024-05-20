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
        Schema::create('hotel', function (Blueprint $table) {
            $table->id();
            $table->text('simple_description_about_hotel')->nullable();
            $table->text('reviews_about_hotel')->nullable();
            $table->string('hotel_name', 45)->nullable();
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
        Schema::dropIfExists('hotel');
    }
};
