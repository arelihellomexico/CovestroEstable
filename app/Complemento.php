<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complemento extends Model
{
    protected $table = "complemento";
    protected $primary_key = "id_comp";
    protected $fillable = [
    	"id_pago",
    	"clearing_document",
		"version",
		"fecha_clearing",
		"regimen",
		"lugarexpedicion",
		"residenciafiscal",
		"numregidtrib",
		"confirmacion",
		"formap",
		"monedaP",
		"fechap",
		"fechabus",
		"tipocambioP",
		"montoP",
		"signo",
		"numeroperP",
		"rfcctaord",
		"bancoordext",
		"ctaord",
		"rfcctaben",
		"cataben",
		"rfc_c",
		"nombre_c",
		"rfc_e",
		"nombre_e",
		"id_cliente",
		"timbrado",
		"id_pro",
		"id_es",
		"USOCFDI",
		"TASAIVA",
		"TASARETENCION",	
    ];

    public $timestamps = false;
}
