<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('card_title');
            $table->boolean('card_title_active')->default(true);
            $table->string('card_subtitle')->nullable();
            $table->boolean('card_subtitle_active')->default(true);
            $table->string('text_color', 7)->default('#000000');
            $table->string('card_background_color', 7)->default('#f8f9fa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_types');
    }
}
