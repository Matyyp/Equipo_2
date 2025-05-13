<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Seed inicial con un correo provisional
        DB::table('settings')->insert([
            ['key' => 'company_email', 'value' => 'proyectotis02@gmail.com'],
            ['key' => 'company_name',  'value' => 'Tenancy Central'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
