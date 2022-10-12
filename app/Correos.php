<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correos extends Model
{
    protected $table = "correos_clientes";
    protected $primary_key = "id";
    protected $fillable = [
    	"correo",
    	"id_cliente",
    ];

    public $timestamps = false;
}
