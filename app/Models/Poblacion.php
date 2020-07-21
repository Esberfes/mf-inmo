<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    protected $table = 'poblaciones';

	public $timestamps = false;

    protected $fillable = [
        'nombre',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion'
    ];

    public function locales()
	{
		return $this->hasMany('App\Models\Local', 'id_poblacion', 'id');
    }
}
