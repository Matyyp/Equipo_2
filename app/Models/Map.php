<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Map extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'maps';
    protected $primaryKey = 'id_map';

    protected $fillable = [
        'map_active',
        'titulo',
        'titulo_active',
        'ciudad',
        'ciudad_active',
        'direccion',
        'direccion_active',
        'contactos',
        'contactos_active',
        'horario',
        'horario_active',
        'texto_boton',
        'boton_active',
        'boton_color',
        'boton_color_texto',
        'color_tarjeta',
        'color_texto_tarjeta',
        'color_mapa',
        'coordenadas_mapa',
        'url_boton'
    ];

    protected $casts = [
        'map_active' => 'boolean',
        'titulo_active' => 'boolean',
        'ciudad_active' => 'boolean',
        'direccion_active' => 'boolean',
        'contactos_active' => 'boolean',
        'horario_active' => 'boolean',
        'boton_active' => 'boolean',
    ];

    public function getContactosArrayAttribute()
    {
        return explode(',', $this->contactos);
    }
}