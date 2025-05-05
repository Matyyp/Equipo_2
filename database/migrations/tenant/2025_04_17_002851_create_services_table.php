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
        Schema::create('services', function (Blueprint $table) {
            $table->id('id_service');
            $table->string('name');
            $table->integer('price_net');
            $table->unsignedBigInteger('id_branch_office');
            $table->foreign('id_branch_office')->references('id_branch')->on('branch_offices')->onDelete('cascade');
            $table->enum('type_service', ['car_wash', 'parking_daily', 'parking_annual', 'rent']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
