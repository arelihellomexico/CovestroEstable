<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tesoreria_Model extends Model
{
    protected $table = "tesoreria";
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
        'usuario',
        'id_et',
    ];
    
    public $timestamps = false;

}
