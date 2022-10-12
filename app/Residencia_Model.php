<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residencia_Model extends Model
{
    protected $table = "residencia";
    protected $primary_key = "id_es";
    protected $fillable = [
    	"nombre",
    	"resid",
    	"equivalencia",
    ];
    public $timestamps = false;
}
