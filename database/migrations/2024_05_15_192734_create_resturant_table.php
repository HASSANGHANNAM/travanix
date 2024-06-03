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
        Schema::create('resturant', function (Blueprint $table) {
            $table->id();
            $table->string('resturant_name', 45)->unique();
            $table->string('phone_number', 45);
            $table->string('descreption', 255);
            $table->time('opining_time');
            $table->time('closing_time');
            $table->float('resturant_class');
            $table->string('type_of_food', 255);
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
        Schema::dropIfExists('resturant');
    }
};
