<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parcialidades extends Model
{
    protected $table = "parcialidades";
    protected $primary_key = "id_par";
    protected $fillable = [
    	"tipcambio",
    	"moneda",
		"numparcialidad",
		"impsaldoant",
		"imppagado",
		"impsaldoins",
		"signo",
		"folio",
    	"clearing_document",
        "rfc_c",
        "nombre_c",
        "id_es",
        "tipo_cambio_bancos",
        "tipo_impuesto",
        "base_impuesto",
        "impuesto",
        "total_impuesto",
        "residencia",
    ];

    public $timestamps = false;
}
