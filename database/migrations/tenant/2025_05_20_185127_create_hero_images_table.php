<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('hero_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hero_id');
            $table->string('path');
            $table->timestamps();

            $table->foreign('hero_id')
                ->references('id_hero')
                ->on('heroes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
}
