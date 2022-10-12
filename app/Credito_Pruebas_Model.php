<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_Pruebas_Model extends Model
{
    protected $table = "temporal_credito";
    protected $primary_key = "id_tc";
    protected $fillable = [
        'folio',
        'clearing',
        'parcialidad',
        'moneda',
        'tipo_cambio',
        'impsaldoant',
        'imppagado',
        'id_ar',
        'nombre_archivo',
        'usuario',
    ];

    public $timestamps = false;
}
