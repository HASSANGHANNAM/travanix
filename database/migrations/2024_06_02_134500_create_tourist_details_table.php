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
        Schema::create('tourist_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tourist_has_id');
            $table->foreign('tourist_has_id')->references('id')->on('tourist_has_trip');
            $table->string('name');
            $table->integer('age');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_details');
    }
};
