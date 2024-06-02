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
        Schema::create('tourist_has_trip', function (Blueprint $table) {
            $table->id();
            $table->enum("payment_status", ["paid", "unpaid"]);
            $table->string('phone_number', 45);
            $table->integer('number_of_seat')->unsigned();
            $table->unsignedBigInteger('trip_id');
            $table->foreign('trip_id')->references('id')->on('trip');
            $table->unsignedBigInteger('tourist_id');
            $table->foreign('tourist_id')->references('id')->on('tourist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_has_trip');
    }
};
