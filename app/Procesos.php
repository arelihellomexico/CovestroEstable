<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Procesos extends Model{
    protected $table = "procesos";
    protected $primary_key = "id_pro";
    protected $fillable = [
    	"nombre",
    	"fecha",
    	"total",
    	"correctos",
    	"erroneos",
    	"integracion",
		"timbrado",
		"obtencion",
        "id_es",
        "id_et",
        "id_ec",
    ];

    public $timestamps = false;
}
