<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->enum('status', ['en_progreso', 'completado'])->default('en_progreso')->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
