<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivos extends Model
{
    protected $table = "archivos";
    protected $primary_key = "id_ar";
    protected $fillable = [
    	"nombre",
        "fecha",
        "fechabus",
    	"ruta",
        "id_cliente",
        "clearing",
        "rfc_cliente",
        "cliente",
    	"timbrado",
        "id_pro",
    	"generapdf",
    	"generaxml",
    ];

    public $timestamps = false;
}
