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
        Schema::table('user_ratings', function (Blueprint $table) {
            $table->string('criterio')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('user_ratings', function (Blueprint $table) {
            $table->string('criterio')->nullable(false)->change(); // o el valor original
        });
    }
};
