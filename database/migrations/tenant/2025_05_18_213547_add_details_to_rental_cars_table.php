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
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->tinyInteger('passenger_capacity')->unsigned()
                ->after('branch_office_id')
                ->comment('Cantidad de pasajeros');
            $table->enum('transmission', ['manual','automatic'])
                ->after('passenger_capacity')
                ->comment('Tipo de transmisión');
            $table->tinyInteger('luggage_capacity')->unsigned()
                ->after('transmission')
                ->comment('Capacidad (n° maletas)');
            $table->decimal('price_per_day', 8, 2)->unsigned()
                ->after('luggage_capacity')
                ->comment('Precio de arriendo por día');
        });
    }

    public function down(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->dropColumn([
            'passenger_capacity',
            'transmission',
            'luggage_capacity',
            'price_per_day',
            ]);
        });
    }
};
