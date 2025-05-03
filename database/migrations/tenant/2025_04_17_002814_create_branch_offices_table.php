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
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id('id_branch');
            $table->string('name_branch_offices');
            $table->string('schedule');
            $table->string('street');
            $table->unsignedBigInteger('id_business');
            $table->unsignedBigInteger('id_location');
            $table->foreign('id_business')->references('id_business')->on('businesses')->onDelete('cascade');
            $table->foreign('id_location')->references('id_location')->on('locations')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');
    }
};
