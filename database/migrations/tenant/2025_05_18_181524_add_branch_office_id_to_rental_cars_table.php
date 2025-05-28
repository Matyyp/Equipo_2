<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            // lo hacemos nullable para no romper las filas viejas
            $table->foreignId('branch_office_id')
                ->nullable()
                ->after('id')
                ->constrained('branch_offices', 'id_branch')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rental_cars', function (Blueprint $table) {
            $table->dropForeign(['branch_office_id']);
            $table->dropColumn('branch_office_id');
        });
    }

};
