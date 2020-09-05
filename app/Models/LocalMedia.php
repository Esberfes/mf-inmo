<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalMedia extends Model
{
    protected $table = 'locales_datos_media';

	public $timestamps = false;

    protected $fillable = [
        'id_local',
        'ruta',
        'tipo',
        'orden',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion'
    ];

    public function local()
    {
        return $this->hasOne('App\Models\Local', 'id', 'id_local');
    }
}
