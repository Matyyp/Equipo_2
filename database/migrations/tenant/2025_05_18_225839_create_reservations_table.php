<?php

// database/migrations/2025_05_XX_create_reservations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()            // users.id
                  ->onDelete('cascade');
            $table->foreignId('car_id')
                  ->constrained('rental_cars', 'id')  // rental_cars.id
                  ->onDelete('cascade');
            $table->foreignId('branch_office_id')
                  ->constrained('branch_offices', 'id_branch')
                  ->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending','confirmed','cancelled'])
                  ->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
