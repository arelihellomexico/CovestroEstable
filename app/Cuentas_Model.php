<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuentas_Model extends Model
{
  protected $table = "cuentas";
  protected $primary_key = "numcuenta";
  protected $fillable = [
      'nombrebanco',
      'RFC_Banco',
      'cuenta_clabe',
  ];

  public $timestamps = false;
}
