<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel_Credito extends Model
{
    protected $table = "excel_credito";
    protected $primary_key = "id_ec";
    protected $fillable = [
        'nombre',
        'fecha',
        'integrado',
        'correo',
        'id_pro',
    ];

    public $timestamps = false;
}
