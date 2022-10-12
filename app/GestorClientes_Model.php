<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GestorClientes_Model extends Model
{
    protected $table = "clientes";
    protected $primary_key = "id_cliente";
    protected $fillable = [
      "rfc_c",
      "nombre_c",
      "nombre2_c",
      "telefono_c",
      "direccion_c",
      "cpostal_c",
      "localidad_c",
      "municipio_c",
      "estado_c",
      "pais_c",
      "residenciafiscal",
      "numregidtrib",
    ];

    public $timestamps = false;
}
