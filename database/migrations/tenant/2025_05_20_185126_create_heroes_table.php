<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroesTable extends Migration
{
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id('id_hero');
            $table->string('title');
            $table->boolean('title_active')->default(true);
            $table->string('subtitle')->nullable();
            $table->boolean('subtitle_active')->default(true);
            $table->string('button_text')->nullable();
            $table->boolean('button_active')->default(false);
            $table->string('button_url')->nullable();
            $table->string('button_color')->default('#000000');
            $table->string('text_color')->default('#FFFFFF');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heros');
    }
}
