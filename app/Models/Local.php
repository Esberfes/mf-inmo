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
        'extracto',
        'relevante',
        'banner_activo',
        'descripcion',
        'creado_en',
        'actualizado_en',
        'id_usuario_actualizacion',
        'id_sector',
        'id_poblacion'
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

    public function sector()
    {
        return $this->hasOne('App\Models\Sector', 'id', 'id_sector');
    }

    public function poblacion()
    {
        return $this->hasOne('App\Models\Poblacion', 'id', 'id_poblacion');
    }
}
