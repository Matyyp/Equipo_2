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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id('id_bank_details');
            $table->string('name');
            $table->integer('rut');
            $table->integer('account_number');
            $table->unsignedBigInteger('id_bank');
            $table->foreign('id_bank')->references('id_bank')->on('banks')->onDelete('cascade');
            $table->unsignedBigInteger('id_type_account');
            $table->foreign('id_type_account')->references('id_type_account')->on('type_accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_deatils');
    }
};
