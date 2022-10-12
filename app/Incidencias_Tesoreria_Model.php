<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencias_Tesoreria_Model extends Model
{
    protected $table = "incidencias_tesoreria";
    protected $primary_key = "id_ites";
    protected $fillable = [
    	"linea",
		"incidencias",
		"nombre_archivo",
		"fecha",
    ];

    public $timestamps = false;
}
