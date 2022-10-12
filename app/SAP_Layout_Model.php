<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SAP_Layout_Model extends Model
{
    protected $table = "bancos_l_SAP";
    protected $primary_key = "id_ls";
    protected $fillable = [
    	"nombre",
        "hoja_sap",
        "hoja_bancos",
        "ID",
        "FOLIO",
        "MONEDAPAGO",
        "MONTOPAGO",
        "TIPOCAMBIOP",
        "FOLIOS",
        "PARCIAL",
        "TIPODOC",
        "FECHAPAGO",
        "NUMREGIDTRIB",
        "REFERENCE",
        "ASSIGNMENT",
        "MONTOPAGOMXN",
        "IMPUESTO",
        "USOCFDI",
		"TASAIVA",
		"TASARETENCION",
    ];
    
    public $timestamps = false;
}
