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
        Schema::create('branch_office', function (Blueprint $table) {
            $table->id('id_branch');
            $table->string('schedule');
            $table->string('street');
            $table->unsignedBigInteger('id_business');
            $table->unsignedBigInteger('id_location');
            $table->foreign('id_business')->references('id_business')->on('business')->onDelete('cascade');
            $table->foreign('id_location')->references('id_location')->on('location')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_office');
    }
};
