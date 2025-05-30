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
        Schema::create('parks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_car');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id_service')->on('parkings')->onDelete('cascade');
            $table->foreign('id_car')->references('id_car')->on('cars')->onDelete('cascade');
            $table->enum('status', ['parked', 'not_parked']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parks');
    }
};
