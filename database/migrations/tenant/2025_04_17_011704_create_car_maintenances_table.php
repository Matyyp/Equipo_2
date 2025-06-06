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
        Schema::create('car_maintenances', function (Blueprint $table) {
            $table->id('id_maintenance');
            $table->string('name_maintenance');
            $table->string('receipt');
            $table->integer('current_km');
            $table->integer('next_km');
            $table->boolean('filtros')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_maintenances');
    }
};
