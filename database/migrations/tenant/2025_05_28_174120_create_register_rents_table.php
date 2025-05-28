<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterRentsTable extends Migration
{
    public function up()
    {
        Schema::create('register_rents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_car_id')->constrained('rental_cars')->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');

            $table->string('driving_license'); 
            $table->string('class_licence');
            $table->date('expire');

            $table->string('address');
            $table->text('observation')->nullable();

            $table->decimal('guarantee', 10, 2)->default(0);
            $table->decimal('payment', 10, 2)->default(0);

            $table->enum('departure_fuel', ['vacío', '1/4', '1/2', '3/4', 'lleno'])->nullable();
            $table->enum('arrival_fuel', ['vacío', '1/4', '1/2', '3/4', 'lleno'])->nullable();

            $table->integer('km_exit')->nullable();
            $table->integer('arrival_km')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('register_rents');
    }
}
