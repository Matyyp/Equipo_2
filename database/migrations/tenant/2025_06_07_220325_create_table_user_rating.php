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
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('register_rent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // usuario registrado
            $table->unsignedBigInteger('external_user_id')->nullable(); // usuario externo
            $table->tinyInteger('stars')->unsigned(); // 1 a 5
            $table->string('criterio'); // Ej: "impuntual", "mal trato"
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->foreign('register_rent_id')->references('id')->on('register_rents')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('external_user_id')->references('id')->on('external_users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__user_rating');
    }
};
