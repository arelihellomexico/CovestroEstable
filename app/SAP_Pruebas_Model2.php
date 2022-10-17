<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SAP_Pruebas_Model extends Model
{
    protected $table = "temporal_SAP";
    protected $primary_key = "id_ts";
    protected $fillable = [
        "id_cliente",
        "REGIMEN",
        "FOLIO",
        "RFC_E",
        "NOMBRE_E",
        "DIRECCION_E",
        "RFC_R",
        "NOMBRE_R",
        "DIRECCION_R",
        "NUMPAGO",
        "MONEDAPAGO",
        "FECHADOC",
        "MONTOPAGO",
        "TIPOCAMBIOP",
        "TIPODOC",
        "ASSIGNMENT",
        "REFERENCE",
        "RESIDENCIAFISCAL",
        "NUMREGIDTRIB",
        "LUGAREXPEDICION",
        "CONFIRMACION",
        "TIPOCADP",
        "CERTP",
        "CADENAP",
        "SELLOP",
        "FOLIOS",
        "ID_DOC",
        "usuario",
    ];

    public $timestamps = false;
}
