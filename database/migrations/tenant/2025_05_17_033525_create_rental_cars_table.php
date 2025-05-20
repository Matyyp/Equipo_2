<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rental_cars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                  ->constrained('brands', 'id_brand')
                  ->onDelete('cascade');
                  
            $table->foreignId('model_car_id')
                  ->constrained('model_cars', 'id_model')
                  ->onDelete('cascade');

            $table->smallInteger('year')->unsigned();
            $table->boolean('is_active')->default(true)
                  ->comment('0 = inactivo, 1 = activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_cars');
    }
};
