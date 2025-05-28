<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_landing_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_card_id')->constrained('service_landings', 'service_card_id')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_landing_images');
    }
};