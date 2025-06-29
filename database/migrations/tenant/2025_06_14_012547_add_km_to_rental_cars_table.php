<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->integer('km')->after('id')->nullable(); // Puedes ajustar la posición y si es nullable
        });
    }

    public function down(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->dropColumn('km');
        });
    }
};