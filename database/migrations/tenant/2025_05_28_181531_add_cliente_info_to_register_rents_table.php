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
            $table->string('client_rut')->nullable()->after('reservation_id');
            $table->string('client_name')->nullable()->after('client_rut');
            $table->string('client_email')->nullable()->after('client_name');
        });
    }

    public function down()
    {
        Schema::table('register_rents', function (Blueprint $table) {
            $table->dropColumn(['client_rut', 'client_name', 'client_email']);
        });
    }

};
