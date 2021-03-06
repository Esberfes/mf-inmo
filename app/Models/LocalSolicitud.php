<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalSolicitud extends Model
{
    protected $table = 'locales_datos_solicitudes';

	public $timestamps = false;

    protected $fillable = [
        'id_local',
        'nombre',
        'email',
        'telefono',
        'comentario',
        'atendido_en',
    ];

    public function local()
    {
        return $this->hasOne('App\Models\Local', 'id', 'id_local');
    }
}
