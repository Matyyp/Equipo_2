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
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')
                ->references('id_service')
                ->on('parkings')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_parking_register');
            $table->foreign('id_parking_register')
                ->references('id_parking_register')
                ->on('parking_registers')
                ->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
