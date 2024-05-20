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
        Schema::create('rating_resturant', function (Blueprint $table) {
            $table->id();
            $table->float('rate')->nullable();
            $table->unsignedBigInteger('resturant_id');
            $table->foreign('resturant_id')->references('id')->on('resturant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_resturant');
    }
};
