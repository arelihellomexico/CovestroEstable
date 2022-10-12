<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bancos_SAP_Model extends Model
{
    protected $table = "bancos_SAP";
    protected $primary_key = "id_bsap";
    protected $fillable = [
    	"clearing_document",
		"monto",
		"montomxn",
        "monedaP",
        "tipocambioP",
		"usuario",
		"id_es",
    ];

    public $timestamps = false;
}
