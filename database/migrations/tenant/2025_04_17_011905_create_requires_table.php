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
        Schema::create('requires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_car');
            $table->unsignedBigInteger('id_maintenance');
            $table->foreign('id_maintenance')->references('id_maintenance')->on('car_maintenance')->onDelete('cascade');
            $table->foreign('id_car')->references('id_car')->on('car')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requires');
    }
};
