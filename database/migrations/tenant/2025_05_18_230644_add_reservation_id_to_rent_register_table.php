<?php

// database/migrations/2025_05_XX_add_reservation_id_to_rent_register_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rent_registers', function (Blueprint $table) {
            $table->foreignId('reservation_id')
                  ->nullable()
                  ->after('id_rent_register')
                  ->constrained('reservations')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rent_registers', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
            $table->dropColumn('reservation_id');
        });
    }
};

