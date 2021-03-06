<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

	public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'comentario',
        'atendido_en',
    ];
}
