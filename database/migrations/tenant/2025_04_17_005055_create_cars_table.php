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
        Schema::create('cars', function (Blueprint $table) {
            $table->id('id_car');
            $table->string('patent')->unique();
            $table->unsignedBigInteger('id_brand');
            $table->foreign('id_brand')->references('id_brand')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('id_branch_office')->nullable();
            $table->foreign('id_branch_office')->references('id_branch')->on('branch_offices')->onDelete('cascade');
            $table->unsignedBigInteger('id_model');
            $table->foreign('id_model')->references('id_model')->on('model_cars')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
