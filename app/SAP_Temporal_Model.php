<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SAP_Temporal_Model extends Model
{
    protected $table = "bancos_p_SAP";
    protected $primary_key = "id_ls";
    protected $fillable = [
    	"nombre",
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
    ];
    
    public $timestamps = false;
}
