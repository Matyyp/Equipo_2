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
        Schema::create('rent_registers', function (Blueprint $table) {
            $table->id('id_rent_register');
            $table->unsignedBigInteger('id_rent');
            $table->foreign('id_rent')->references('id_service')->on('rents')->onDelete('cascade');
            $table->string('return_in');
            $table->string('address');
            $table->string('driving_licence');
            $table->string('class_licence');
            $table->date('expire');
            $table->text('observation')->nullable();
            $table->integer('guarantee'); 
            $table->string('payment');
            $table->integer('departure_fuel');
            $table->integer('arrival_fuel');
            $table->integer('arrival_km');
            $table->integer('km_exit');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_value');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_registers');
    }
};
