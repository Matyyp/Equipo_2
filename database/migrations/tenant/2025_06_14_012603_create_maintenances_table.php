<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_car_id')->constrained('rental_cars')->onDelete('cascade');
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->onDelete('cascade');

            $table->integer('scheduled_km')->nullable();       // Km programado
            $table->date('scheduled_date')->nullable();        // Fecha programada
            $table->boolean('is_completed')->default(false);   // Si fue realizada

            // Campos que se completan al marcar como realizada
            $table->string('employee_name')->nullable();
            $table->date('completed_date')->nullable();
            $table->string('location')->nullable();            // Lugar donde se hizo
            $table->string('invoice_number')->nullable();      // Número de factura
            $table->string('invoice_file')->nullable();        // (Ruta o filename, opcional si no hay imagen)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
}
