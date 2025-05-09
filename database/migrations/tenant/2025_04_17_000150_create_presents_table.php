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
        Schema::create('presents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contact_information');
            $table->unsignedBigInteger('id_contract');
            $table->foreign('id_contact_information')->references('id_contact_information')->on('contact_information')->onDelete('cascade');
            $table->foreign('id_contract')->references('id_contract')->on('contracts')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presents');
    }
};
