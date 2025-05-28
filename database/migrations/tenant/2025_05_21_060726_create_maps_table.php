<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id('id_map');
            $table->boolean('map_active')->default(true);
            $table->string('titulo');
            $table->boolean('titulo_active')->default(true);
            $table->string('ciudad');
            $table->boolean('ciudad_active')->default(true);
            $table->string('direccion');
            $table->boolean('direccion_active')->default(true);
            $table->text('contactos')->nullable(); // Separados por coma
            $table->boolean('contactos_active')->default(true);
            $table->text('horario');
            $table->boolean('horario_active')->default(true);
            $table->string('texto_boton');
            $table->boolean('boton_active')->default(true);
            $table->string('boton_color')->default('#ffffff');
            $table->string('boton_color_texto')->default('#000000');
            $table->string('color_tarjeta')->default('#ffffff');
            $table->string('color_texto_tarjeta')->default('#000000');
            $table->string('color_mapa')->default('#ffffff');
            $table->string('coordenadas_mapa');
            $table->string('url_boton')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maps');
    }
};