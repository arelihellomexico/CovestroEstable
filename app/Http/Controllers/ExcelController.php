<?php

namespace App\Http\Controllers;

use App\ExcelModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function import(Request $request){
    	
    	Excel::load($request->excel, function($reader){
    		$excel = $reader->get();
    		$reader->each(function($row){
    			$datos = new ExcelModel;
				$datos->a = $row->rfc_cliente;
				$datos->b = $row->total;
				$datos->c = $row->monedita;
				$datos->d = $row->vale;
				$datos->e = $row->fecha;
				$datos->f = $row->operacion;
				$datos->g = $row->rfc_beneficiario;
				$datos->h = $row->cuenta_ben;
				$datos->i = $row->forma_pago;
				$datos->j = $row->rfc_banco_cliente;
				$datos->k = $row->banco_cliente;
				$datos->l = $row->cuenta_cliente;
				$datos->save();
    		});
    	});
		$dato = DB::table('datos')
		->get();
    	return view('generador', ['datos'=>$dato]);
     }
}
