<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Covestro_Model extends Model
{
    protected $table = "covestro";
    protected $primary_key = "rfc_e";
    protected $fillable = [
    "nombre_e",
		"calle_e",
		"numext_e",
		"numint_e",
		"colonia_e",
		"cpostal_e",
		"localidad_e",
		"referencia_e",
		"municipio_e",
		"estado_e",
		"pais_e",
		"regimen",
		"numpago",
		"version_complemento",
		"version_fiscal",
		"metpago",
		"usar_credito",
		"USOCFDI",
        "TASAIVA",
        "TASARETENCION",
    ];

    public $timestamps = false;
}
