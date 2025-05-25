<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservation_payment', function (Blueprint $table) {
            // Agregar clave foránea a reservations
            $table->foreignId('reservation_id')
                  ->nullable()
                  ->constrained('reservations')
                  ->onDelete('cascade');

            // Agregar columnas opcionales
            $table->string('token')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('response_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reservation_payment', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
            $table->dropColumn([
                'reservation_id',
                'token',
                'authorization_code',
                'payment_type',
                'response_code'
            ]);
        });
    }
};
