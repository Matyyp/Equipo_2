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
        Schema::create('belongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_owner');
            $table->unsignedBigInteger('id_car');
            $table->foreign('id_owner')->references('id_owner')->on('owners')->onDelete('cascade');
            $table->foreign('id_car')->references('id_car')->on('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belongs');
    }
};
