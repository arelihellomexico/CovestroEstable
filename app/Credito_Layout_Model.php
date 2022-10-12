<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_Layout_Model extends Model
{
    protected $table = "bancos_l_credito";
    protected $primary_key = "id_lc";
    protected $fillable = [
    	'nombre',
    	'folio',
        'clearing',
        'parcialidad',
        'moneda',
        'tipo_cambio',
    	'impsaldoant',
    	'imppagado',
    ];

    public $timestamps = false;
}
