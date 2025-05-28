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
        Schema::create('navbars', function (Blueprint $table) {
            $table->id();
            $table->string('reservations');
            $table->boolean('reservations_active')->default(true);
            $table->string('schedule');
            $table->boolean('schedule_active')->default(true);
            $table->string('email');
            $table->boolean('email_active')->default(true);
            $table->string('address');
            $table->boolean('address_active')->default(true);
            $table->string('services');
            $table->boolean('services_active')->default(true);
            $table->string('about_us');
            $table->boolean('about_us_active')->default(true);
            $table->string('contact_us');
            $table->boolean('contact_us_active')->default(true);
            $table->string('background_color_1');
            $table->string('background_color_2');
            $table->string('button_1');
            $table->string('button_color_1');
            $table->boolean('button_1_active')->default(true);
            $table->string('button_2');
            $table->string('button_color_2');
            $table->boolean('button_2_active')->default(true);
            $table->string('text_color_1');
            $table->string('text_color_2');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navbars');
    }
};
