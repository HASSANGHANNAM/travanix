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
            $table->float('price_trip')->unsigned();
            $table->integer('number_of_allSeat')->unsigned();
            $table->text('reviews_about_trip')->nullable();
            $table->string('type_of_trip', 45);
            $table->dateTime('trip_start_time');
            $table->dateTime('trip_end_time');
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
