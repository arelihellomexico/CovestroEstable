<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bancos_Layout_Model extends Model
{
    protected $table = "bancos_l_tesoreria";
    protected $primary_key = "id_lt";
    protected $fillable = [
        'nombre',
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
    ];
    public $timestamps = false;
}
?>
