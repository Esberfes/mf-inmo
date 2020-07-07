<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalEquipamiento extends Model
{
    protected $table = 'locales_datos_equipamiento';

	public $timestamps = false;

    protected $fillable = [
        'id_local',
        'valor',
        'orden',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion'
    ];
}
