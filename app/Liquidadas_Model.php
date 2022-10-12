<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidadas_Model extends Model
{
    protected $table = "facturas_liquidadas";
    protected $primary_key = "id_cre";
    protected $fillable = [
        'folio',
        'clearing_document',
        'numparcialidad',
        'moneda',
        'tipcambio',
        'impsaldoant',
        'imppagado',
        'impsaldoins',
        'id_es',
    ];

    public $timestamps = false;
}
