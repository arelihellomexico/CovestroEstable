<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bancos_Pruebas_Model extends Model
{
    protected $table = "temporal_tesoreria";
    protected $primary_key = "id_tt";
    protected $fillable = [
    	'RFC_R',
    	'FORMAP',
    	'MONEDAP',
    	'TIPOCAMBIOP',
    	'MONTOP',
    	'NUMEROPERP',
    	'RFCCTAORD',
    	'BANCOORDEXT',
    	'CTAORD',
    	'RFCCTABEN',
    	'CATABEN',
        'FECHAPAG',
        'id_ar',
        'nombre_archivo',
        'usuario',
    ];
    public $timestamps = false;
}
