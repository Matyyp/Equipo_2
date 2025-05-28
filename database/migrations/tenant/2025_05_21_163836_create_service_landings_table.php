<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_landings', function (Blueprint $table) {
            $table->id('service_card_id');
            $table->string('title');
            $table->boolean('title_active')->default(true);
            $table->string('secondary_text')->nullable();
            $table->boolean('secondary_text_active')->default(true);
            $table->string('small_text')->nullable();
            $table->boolean('small_text_active')->default(true);
            $table->string('title_color')->default('#000000');
            $table->string('secondary_text_color')->default('#000000');
            $table->string('small_text_color')->default('#000000');
            $table->string('card_background_color')->default('#FFFFFF');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_landings');
    }
};