<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            
            // Text content
            $table->text('top_text')->nullable();
            $table->boolean('top_text_active')->default(true);
            $table->string('main_title')->nullable();
            $table->boolean('main_title_active')->default(true);
            $table->text('secondary_text')->nullable();
            $table->boolean('secondary_text_active')->default(true);
            $table->text('tertiary_text')->nullable();
            $table->boolean('tertiary_text_active')->default(true);
            
            // Button
            $table->string('button_text')->nullable();
            $table->boolean('button_active')->default(true);
            $table->string('button_link')->nullable();
            
            // Colors
            $table->string('button_text_color')->default('#ffffff');
            $table->string('button_color')->default('#f97316'); // orange-500
            $table->string('card_color')->default('#0b1a2b');
            $table->string('card_text_color')->default('#ffffff');
            $table->string('video_card_color')->default('#1e293b');
            
            // Videos
            $table->text('video_links')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('about_us');
    }
};