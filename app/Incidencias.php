<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencias extends Model
{
    protected $table = "incidencias";
    protected $primary_key = "id_inci";
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
    ];

    public $timestamps = false;
}
