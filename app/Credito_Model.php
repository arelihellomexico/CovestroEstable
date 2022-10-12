<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_Model extends Model
{
    protected $table = "credito";
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
        'id_ec',
    ];

    public $timestamps = false;
}
