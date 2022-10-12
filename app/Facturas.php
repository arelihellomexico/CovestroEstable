<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    protected $table = "factura";
    protected $primary_key = "folio";
    protected $fillable = [
    	"uuid",
    	"monto",
    	"moneda",
        "tipo_cambio",
    	"fecha",
    	"tipo_impuesto",
    	"impuesto",
        "sin_impuesto",
        "id_cliente",
        "nombre_c",
        "residencia",
        "clearings",
        "monto_mxn",
        "USOCFDI",
        "TASAIVA",
        "TASARETENCION"
    ];

    public $timestamps = false;
}
