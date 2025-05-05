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
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id('id_payment');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')
                ->references('id_service')
                ->on('payments')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_voucher')->nullable();
            $table->foreign('id_voucher')
                ->references('id_voucher')
                ->on('payments')
                ->onDelete('cascade');
            $table->integer('amount');
            $table->string('type_payment'); 
            $table->date('payment_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};
