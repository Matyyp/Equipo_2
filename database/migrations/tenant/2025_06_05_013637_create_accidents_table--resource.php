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
        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->string('name_accident');
            $table->text('description')->nullable();
            $table->string('bill_number')->nullable();
            $table->text('description_accident_term')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['in progress', 'complete'])->default('in progress');
            $table->unsignedBigInteger('rental_car_id');
            $table->foreign('rental_car_id')->references('id')->on('rental_cars')->onDelete('cascade');
            $table->unsignedBigInteger('id_rent');
            $table->foreign('id_rent')->references('id')->on('register_rents')->onDelete('cascade');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidents');
    }
};