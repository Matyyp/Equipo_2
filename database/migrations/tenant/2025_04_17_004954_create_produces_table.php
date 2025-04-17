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
        Schema::create('produces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rent_register');
            $table->unsignedBigInteger('id_contract');
            $table->foreign('id_contract')->references('id_contract')->on('contract_rents')->onDelete('cascade');
            $table->foreign('id_rent_register')->references('id_rent_register')->on('rent_registers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produces');
    }
};
