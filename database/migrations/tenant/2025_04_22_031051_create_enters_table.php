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
        Schema::create('enters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rent');
            $table->foreign('id_rent')
                ->references('id_service')
                ->on('rents')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_rent_register');
            $table->foreign('id_rent_register')
                ->references('id_rent_register')
                ->on('rent_registers')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enters');
    }
};
