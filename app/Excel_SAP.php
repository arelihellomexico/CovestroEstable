<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel_SAP extends Model
{
    protected $table = "excel_SAP";
    protected $primary_key = "id_es";
    protected $fillable = [
    	"nombre",
    	"fecha",
    	"integrado",
    	"id_pro",
    	"correo",
    ];

    public $timestamps = false;
}
