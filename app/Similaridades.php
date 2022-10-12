<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Similaridades extends Model
{
    protected $table = "similaridades";
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
        'timbrado',
    ];
    
    public $timestamps = false;
}
