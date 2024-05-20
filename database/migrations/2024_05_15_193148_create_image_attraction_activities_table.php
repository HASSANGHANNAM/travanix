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
        Schema::create('image_attraction_activities', function (Blueprint $table) {
            $table->id();
            $table->text('path_of_image')->nullable();
            $table->unsignedBigInteger('attraction_activities_id');
            $table->foreign('attraction_activities_id')->references('id')->on('attraction_activities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_attraction_activities');
    }
};
