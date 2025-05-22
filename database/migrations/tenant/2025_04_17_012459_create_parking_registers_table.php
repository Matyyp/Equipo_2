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
        Schema::create('parking_registers', function (Blueprint $table) {
            $table->id('id_parking_register');
            $table->integer('arrival_km')->nullable();
            $table->integer('km_exit')->nullable();
            $table->integer('total_value');
            $table->integer('days');
            $table->integer('id_park');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['paid', 'unpaid']);
            $table->boolean('washed')->nullable();
            $table->unsignedBigInteger('id_service')->nullable();
            $table->foreign('id_service')->references('id_service')->on('car_washes')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_registers');
    }
};
