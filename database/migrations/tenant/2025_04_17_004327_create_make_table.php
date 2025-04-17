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
        Schema::create('make', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_voucher');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            $table->foreign('id_voucher')->references('id_voucher')->on('voucher')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('make');
    }
};
