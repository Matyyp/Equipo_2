<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberPhoneToRegisterRentsTable extends Migration
{
    public function up()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->string('number_phone')->nullable()->after('client_email');
        });
    }

    public function down()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->dropColumn('number_phone');
        });
    }
}
