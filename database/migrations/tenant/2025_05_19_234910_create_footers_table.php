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
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('copyright');
            $table->text('contact_text')->nullable(); // separado por comas
            $table->boolean('contact_active')->default(true);
            $table->text('social_text')->nullable(); // separado por comas
            $table->boolean('social_active')->default(true);
            $table->string('background_color')->default('#ffffff');
            $table->string('text_color_1')->default('#000000');
            $table->string('text_color_2')->default('#000000');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};
