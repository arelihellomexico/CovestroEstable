<?php

namespace App\Http\Controllers;

use App\Credito_Layout_Model;
use App\Credito_Pruebas_Model;
use App\Credito_Model;
use App\Bancos_Pruebas_Model;
use App\Pagos;
use App\Facturas;
use App\Tesoreria_Model;
use App\Covestro_Model;
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

        DB::table("excel_credito")
        ->where("integrado", "=", 3)
        ->where("correo", "=", Session::get("user"))
        ->delete();

        DB::table("temporal_credito")
        ->where("usuario", "=", Session::get("usuario"))
        ->delete();

    	return view('Credito.validacionCredito',["layout" => $layouts]);
    }

    public function guardarPrueba(Request $request){

        DB::table("excel_credito")
        ->where("integrado", "=", 3)
        ->where("correo", "=", Session::get("user"))
        ->delete();

        try {
            Session::put('layout', $request->get('layout'));
            $elimina = DB::table("temporal_credito")
            ->delete();
            
            foreach ($request->excel as $archivo) {
                Session::put('nombre_archivo_credito', $archivo->getClientOriginalName());
                $id_ar = DB::table('excel_tesoreria')->insertGetId(
                    ['nombre' => Session::get('nombre_archivo_credito'), 'fecha' => date("Y-m-d"), 'integrado' => 3, "id_pro" => 0, "correo" => Session::get('user')]
                );
                Session::put('num_archivo_c', $id_ar);

                Excel::load($archivo, function($reader){
                    $lay = DB::table('bancos_l_credito')
                    ->where('id_lc', '=', Session::get('layout'))
                    ->first();
                    $FOLIO = $lay->folio;
                    $CLEARING = $lay->clearing;
                    $PARCIALIDAD = $lay->parcialidad;
                    $MONEDA = $lay->moneda;
                    $CAMBIO = $lay->tipo_cambio;
                    $IMPSALDOANT = $lay->impsaldoant;
                    $IMPPAGADO = $lay->imppagado;
                    foreach ($reader->get() as $key => $row) {
                        $sap = new Credito_Pruebas_Model;
                        $sap->folio = $row[$FOLIO];
                        $sap->clearing = date("Y").$row[$CLEARING];
                        $sap->parcialidad = $row[$PARCIALIDAD];
                        $sap->moneda = $row[$MONEDA];
                        $sap->tipo_cambio = $row[$CAMBIO];
                        $sap->impsaldoant = $row[$IMPSALDOANT];
                        $sap->imppagado = $row[$IMPPAGADO];
                        $sap->id_ar = Session::get("num_archivo_c");
                        $sap->nombre_archivo = Session::get("nombre_archivo_credito");
                        $sap->usuario = Session::get('user');
                        $sap->save();
                    }
                });
            }

            $mostrar = DB::table('temporal_credito')
            ->where('usuario', '=', Session::get('user'))
            ->get();

            return response()->json($mostrar);
        } catch (\Exception $e) {
            return response()->json([
                "respuesta" => 2,
                "mensaje" => $e->getMessage(),
                "archivo" => Session::get('nombre_archivo_credito')
            ]);
        }
    }

    public function covestro(Request $request)
    {
    	$covestro = DB::table('covestro')
    	->first();

    	return response()->json($covestro);
    }

    public function hacerPrueba(Request $request)
    {
    	Session::put('layout', $request->get('layout'));
    	Excel::load($request->excel, function($reader){
    		$excel = $reader->get();
    		$reader->each(function($row){
    			$lay = DB::table('bancos_l_tesoreria')
    			->where('id_lt', '=', Session::get('layout'))
    			->first();
    			$RFC_R = $lay->RFC_R;
    			$MONTOP = $lay->MONTOP;
    			$MONEDAP = $lay->MONEDAP;
    			$NUMEROPERP = $lay->NUMEROPERP;
    			$RFCCTABEN = $lay->RFCCTABEN;
    			$CATABEN = $lay->CATABEN;
    			$FORMAP = $lay->FORMAP;
    			$RFCCTAORD = $lay->RFCCTAORD;
    			$BANCOORDEXT = $lay->BANCOORDEXT;
    			$CTAORD = $lay->CTAORD;
    			$FECHAPAG = $lay->FECHAPAG;
				$datos = new Bancos_Pruebas_Model;
				$datos->RFC_R = $row->$RFC_R;
				$datos->MONTOP = $row->$MONTOP;
				$datos->MONEDAP = $row->$MONEDAP;
				$datos->NUMEROPERP = $row->$NUMEROPERP;
				$datos->RFCCTABEN = $row->$RFCCTABEN;
				$datos->CATABEN = $row->$CATABEN;
				$datos->FORMAP = $row->$FORMAP;
				$datos->RFCCTAORD = $row->$RFCCTAORD;
				$datos->BANCOORDEXT = $row->$BANCOORDEXT;
				$datos->CTAORD = $row->$CTAORD;
				$datos->FECHAPAG = $row->$FECHAPAG;
				$datos->usuario = Session::get('user');
				$datos->save();
    		});
    	});

    	$mostrar = DB::table('temporal_credito')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	return response()->json($mostrar);
    }

    public function guardarDatos(Request $request)
    {
    	$prueba = DB::table('temporal_credito')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	foreach($prueba as $p){
			$teso = new Credito_Model;
			$teso->folio = $p->folio;
			$teso->clearing_document = $p->clearing;
			$teso->numparcialidad = $p->parcialidad;
			$teso->moneda = $p->moneda;
			$teso->tipcambio = $p->tipo_cambio;
			$teso->impsaldoant = $p->impsaldoant;
			$teso->imppagado = $p->imppagado;
			$teso->impsaldoins = round((float)$p->impsaldoant - (float)$p->imppagado, 2);
			$teso->id_ec = Session::get("num_archivo_c");
			$teso->save();
    	}

    	$mostrar = DB::table('temporal_credito')
        ->where('usuario', '=', Session::get('user'))
        ->count();

        DB::table("excel_credito")
        ->where("integrado", "=", 3)
        ->update([
            "integrado" => 0
        ]);

        if($mostrar > 0){
            $mostrar2 = DB::table('temporal_credito')
            ->where('usuario', '=', Session::get('user'))
            ->delete();

            return response()->json($mostrar);
        }
        else{
            DB::table("excel_credito")
            ->where("integrado", "=", 3)
            ->delete();

            return response()->json([
                "respuesta" => "2"
            ]);
        }
    }

    public function borrarPrueba(Request $request)
    {
    	$mostrar = DB::table('temporal_credito')
    	->where('usuario', '=', Session::get('user'))
    	->delete();

        DB::table("excel_credito")
        ->where("integrado", "=", 3)
        ->where("correo", "=", Session::get("user"))
        ->delete();

        /*$mostrar = DB::table('tesoreria')
        ->delete();*/

    	return response()->json([
    		"respuesta" => "si"
    	]);
    }
}
