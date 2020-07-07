<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

	public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'pass',
        'rol',
        'ultimo_login',
        'creado_en',
        'actualizado_en'
    ];


    protected $hidden = [
        'pass',
    ];
}
