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
        Schema::create('annual_contracts', function (Blueprint $table) {
            $table->id('id_contract');
            $table->foreign('id_contract')->references('id_contract')->on('contract_parkings')->onDelete('cascade');
            $table->integer('rut');
            $table->string('authorized_person');
            $table->string('important_note');
            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_contracts');
    }
};
