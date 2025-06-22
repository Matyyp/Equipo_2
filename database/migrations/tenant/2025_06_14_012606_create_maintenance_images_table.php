<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_id')->constrained('maintenances')->onDelete('cascade');
            $table->string('image_path'); // Ruta o nombre del archivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_images');
    }
}
