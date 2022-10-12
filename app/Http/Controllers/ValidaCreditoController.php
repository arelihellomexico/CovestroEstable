<?php

namespace App\Http\Controllers;

use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
use App\Bancos_Layout_Model;
use App\Bancos_Pruebas_Model;
use App\Pagos;
use App\Facturas;
use App\Tesoreria_Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ValidacionCreditoController extends Controller
{
    public function index()
    {
    	$layouts = DB::table('bancos_l_credito')
    	->get();

    	return view('validatecredit',["layouts" => $layouts]);
    }

    public function guardarPrueba(Request $request){
    	Session::put('layout', $request->get('layout'));
    	Excel::load($request->excel, function($reader){
    		$excel = $reader->get();
    		$reader->each(function($row){
    			$lay = DB::table('bancos_l_credito')
    			->where('id_lt', '=', Session::get('layout'))
    			->first();
		        $MONEDA = $request->MONEDA;
		        $TIPCAMBIO = $request->TIPCAMBIO;
		        $FOLIO = $request->FOLIO;
		        $NUMPARCIALIDAD = $request->NUMPARCIALIDAD;
		        $IMPSALDOANT = $request->IMPSALDOANT;
		        $IMPPAGADO = $request->IMPPAGADO;
		        $IMPSALDOINS = $request->IMPSALDOINS;
				$datos = new Bancos_Pruebas_Model;
				$datos->RFC_R = $row->$RFC_R;
				$datos->MONTOP = $row->$MONTOP;
				$datos->MONEDAP = $row->$MONEDAP;
				$datos->TIPOCAMBIOP = $row->$TIPOCAMBIOP;
				$datos->NUMEROPERP = $row->$NUMEROPERP;
				$datos->RFCCTABEN = $row->$RFCCTABEN;
				$datos->CATABEN = $row->$CATABEN;
				$datos->FORMAP = $row->$FORMAP;
				$datos->RFCCTAORD = $row->$FORMAP;
				$datos->BANCOORDEXT = $row->$BANCOORDEXT;
				$datos->CTAORD = $row->$CTAORD;
				$datos->FECHAPAG = $row->$FECHAPAG;
				$datos->usuario = Session::get('user');
				$datos->save();
    		});
    	});

    	$mostrar = DB::table('temporal_tesoreria')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	return response()->json($mostrar);
    }

    public function guardarDatos(Request $request)
    {
    	$prueba = DB::table('temporal_tesoreria')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	foreach($prueba as $p){
			$teso = new Tesoreria_Model;
			$teso->RFC_R = $p->RFC_R;
			$teso->MONTOP = $p->MONTOP;
			$teso->MONEDAP = $p->MONEDAP;
			$teso->TIPOCAMBIOP = $p->TIPOCAMBIOP;
			$teso->NUMEROPERP = $p->NUMEROPERP;
			$teso->RFCCTABEN = $p->RFCCTABEN;
			$teso->CATABEN = $p->CATABEN;
			$teso->FORMAP = $p->FORMAP;
			$teso->RFCCTAORD = $p->FORMAP;
			$teso->BANCOORDEXT = $p->BANCOORDEXT;
			$teso->CTAORD = $p->CTAORD;
			$teso->FECHAPAG = $p->FECHAPAG;
			$teso->save();
    	}

    	$mostrar = DB::table('temporal_tesoreria')
    	->where('usuario', '=', Session::get('user'))
    	->delete();

    	return response()->json($mostrar);
    }
}
