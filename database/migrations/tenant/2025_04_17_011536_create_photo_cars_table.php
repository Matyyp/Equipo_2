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
        Schema::create('photo_cars', function (Blueprint $table) {
            $table->id('id_photo_car');
            $table->string('url_photo_car');
            $table->unsignedBigInteger('id_car');
            $table->foreign('id_car')->references('id_car')->on('cars')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_cars');
    }
};
