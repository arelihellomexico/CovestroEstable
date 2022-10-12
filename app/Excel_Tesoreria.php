<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel_Tesoreria extends Model
{
    protected $table = "excel_tesoreria";
    protected $primary_key = "id_et";
    protected $fillable = [
    	"nombre",
    	"fecha",
    	"integrado",
    	"id_pro",
    	"correo",
    ];

    public $timestamps = false;
}
