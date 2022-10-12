<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "usuarios";
	protected $primary_key = "cwid";
    protected $fillable = [
        'nombre',
        'correo',
        'tipo',
        'responsable',
    ];

    public $timestamps = false;
}
