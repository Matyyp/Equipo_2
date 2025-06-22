<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTypesTable extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Oil Change, Tire Rotation
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_types');
    }
}
