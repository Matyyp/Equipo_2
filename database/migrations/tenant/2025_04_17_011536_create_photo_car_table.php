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
        Schema::create('photo_car', function (Blueprint $table) {
            $table->id('id_photo_car');
            $table->string('url_photo_car');
            $table->unsignedBigInteger('id_car');
            $table->foreign('id_car')->references('id_car')->on('car')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_car');
    }
};
