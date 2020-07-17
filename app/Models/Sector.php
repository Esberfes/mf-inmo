<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sectores';

	public $timestamps = false;

    protected $fillable = [
        'titulo',
        'orden',
        'descripcion',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion'
    ];

    public function locales()
	{
		return $this->hasMany('App\Models\Local', 'id_sector', 'id');
    }
}
