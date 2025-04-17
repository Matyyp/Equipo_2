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
        Schema::create('park', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_car');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id_service')->on('parking')->onDelete('cascade');
            $table->foreign('id_car')->references('id_car')->on('car')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('park');
    }
};
