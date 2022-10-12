<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelModel extends Model
{
	protected $table = "datos";
	protected $primary_key = "id";
    protected $fillable = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
    ];
}
