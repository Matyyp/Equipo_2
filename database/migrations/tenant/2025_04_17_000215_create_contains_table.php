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
        Schema::create('contains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rule');
            $table->unsignedBigInteger('id_contract');
            $table->foreign('id_rule')->references('id_rule')->on('rules')->onDelete('cascade');
            $table->foreign('id_contract')->references('id_contract')->on('contracts')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contains');
    }
};
