<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = "pago";
    protected $primary_key = "id_pago";
    protected $fillable = [
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
		"fechadoc",
		"fechabus",
		"assignment",
		"reference",
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
		"id_es",
		"USOCFDI",
		"TASAIVA",
		"TASARETENCION",
    ];

    public $timestamps = false;
}
