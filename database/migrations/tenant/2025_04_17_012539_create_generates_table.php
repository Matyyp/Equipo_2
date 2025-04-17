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
        Schema::create('generates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contract');
            $table->unsignedBigInteger('id_parking_register');
            $table->foreign('id_contract')->references('id_contract')->on('contract_parking')->onDelete('cascade');
            $table->foreign('id_parking_register')->references('id_parking_register')->on('parking_register')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generates');
    }
};
