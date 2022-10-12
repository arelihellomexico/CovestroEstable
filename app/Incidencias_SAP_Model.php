<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencias_SAP_Model extends Model
{
    protected $table = "incidencias_SAP";
    protected $primary_key = "id_isap";
    protected $fillable = [
    	"clearing",
		"incidencias",
		"nombre_archivo",
		"fecha",
    ];

    public $timestamps = false;
}
