<?php

namespace App\Http\Controllers;

use App\Bancos_Layout_Model;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ExcelPruebaController extends Controller
{
    public function guardar(Request $request){
    	Excel::load($request->excel, function($reader){
    		$excel = $reader->get();
    		$reader->each(function($row){
    			$datos = new Bancos_Layout_Model;
				$datos->RFC_R = $row->rfc_cliente;
				$datos->MONTOP = $row->total;
				$datos->MONEDAP = $row->monedita;
				$datos->TIPOCAMBIOP = $row->vale;
				$datos->NUMEROPERP = $row->operacion;
				$datos->RFCCTABEN = $row->rfc_beneficiario;
				$datos->CATABEN = $row->cuenta_ben;
				$datos->FORMAP = $row->forma_pago;
				$datos->RFCCTAORD = $row->rfc_banco_cliente;
				$datos->BANCOORDEXT = $row->banco_cliente;
				$datos->CTAORD = $row->cuenta_cliente;
				$datos->FECHAPAG = $row->fecha;
				$datos->save();
    		});
    	});

    	$dato = DB::table('bancos_l_tesoreria')
		->get();
		
    	return view('generador', ['datos'=>$dato]);
    }
}
