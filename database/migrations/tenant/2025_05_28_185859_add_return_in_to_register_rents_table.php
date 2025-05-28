<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnInToRegisterRentsTable extends Migration
{
    public function up()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->integer('return_in')->default(0)->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->dropColumn('return_in');
        });
    }
}