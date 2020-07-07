<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $table = 'locales';

	public $timestamps = false;

    protected $fillable = [
        'titulo',
        'url_amigable',
        'telefono',
        'precio',
        'metros',
        'precio_metro',
        'extracto',
        'descripcion',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion'
    ];

    public function caracteristicas()
	{
		return $this->hasMany('App\Models\LocalCaracteristica', 'id_local', 'id');
    }

    public function edificios()
	{
		return $this->hasMany('App\Models\LocalEdificio', 'id_local', 'id');
    }

    public function equipamientos()
	{
		return $this->hasMany('App\Models\LocalEquipamiento', 'id_local', 'id');
    }

    public function medias()
	{
		return $this->hasMany('App\Models\LocalMedia', 'id_local', 'id');
    }
}
