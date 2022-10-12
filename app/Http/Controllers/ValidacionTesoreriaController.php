<?php

namespace App\Http\Controllers;
 
use App\Bancos_Pruebas_Model;
 
use App\Tesoreria_Model; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionTesoreriaController extends Controller
{
    public function index()
    {
       
        DB::table("temporal_tesoreria")
        ->where("usuario", "=", Session::get("usuario"))
        ->delete();

    	$layouts = DB::table('bancos_l_tesoreria')
    	->get();

        DB::table("excel_tesoreria")
        ->where("integrado", "=", 3)
        ->where("correo", "=", Session::get("user"))
        ->delete();

    	return view('Tesoreria.validacionTesoreria',["layout" => $layouts]);
    }

    public function guardarPrueba(Request $request){

        $email = session()->get('user');

        DB::table('excel_tesoreria')
        ->where([
            ['integrado', 3],
            ['correo', $email],
        ])
        ->delete();
 
        
        try {
            Session::put('layout', $request->get('layout'));
            $elimina = DB::table("temporal_tesoreria")
            ->delete();
          
            foreach ($request->excel as $archivo) {
                Session::put('nombre_archivo_tesoreria', $archivo->getClientOriginalName());
                $id_ar = DB::table('excel_tesoreria')->insertGetId(
                    ['nombre' => Session::get('nombre_archivo_tesoreria'), 'fecha' => date("Y-m-d"), 'integrado' => 3, "id_pro" => 0, 'correo' => $email]
                );
                Session::put('num_archivo_t', $id_ar);
                Excel::load($archivo, function($reader) use($email){
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
                    
                    foreach ($reader->get() as $key => $row) {
                        $datos = new Bancos_Pruebas_Model;
                        $datos->RFC_R = $row[$RFC_R];
                        $datos->MONTOP = $row[$MONTOP];
                        $datos->MONEDAP = $row[$MONEDAP];
                        $datos->NUMEROPERP = $row[$NUMEROPERP];
                        $datos->RFCCTABEN = $row[$RFCCTABEN];
                        $datos->CATABEN = $row[$CATABEN];
                        $datos->FORMAP = $row[$FORMAP];
                        $datos->RFCCTAORD = $row[$RFCCTAORD];
                        $datos->BANCOORDEXT = $row[$BANCOORDEXT];
                        $datos->CTAORD = $row[$CTAORD];
                        $f = str_split($row[$FECHAPAG]);
                        if(count($f) < 19){
                            $datos->FECHAPAG = $f[5].$f[6].$f[7].$f[8]."-".$f[3].$f[4]."-".$f[1].$f[2]." 00:00:00.000";
                        }
                        else{
                            $datos->FECHAPAG = $row[$FECHAPAG];
                        }
                        $datos->id_ar = Session::get('num_archivo_t');
                        $datos->nombre_archivo = Session::get('nombre_archivo_tesoreria');
                        $datos->usuario = $email;
                        $datos->save();
                    }
                });
            }

            $mostrar = DB::table('temporal_tesoreria')
            ->where('usuario', '=', $email)
            ->get();
            
            return response()->json($mostrar);
        } catch (\Exception $e) {
   
            return response()->json([
                "respuesta" => 2,
                "mensaje" => $e->getMessage(),
                "archivo" => Session::get('nombre_archivo_tesoreria')
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
            $monto = str_replace("-", "", $p->MONTOP);
            $monto = str_replace(" ", "", $monto);
            $monto = str_replace("$", "", $monto);
            $monto = str_replace(",", "", $monto);
            $monto = str_replace("MXN", "", $monto);
            $monto = str_replace("mxn", "", $monto);
			$teso->MONTOP = (float)$monto;
			$teso->MONEDAP = $p->MONEDAP;
			$teso->NUMEROPERP = $p->NUMEROPERP;
			$teso->RFCCTABEN = $p->RFCCTABEN;
			$teso->CATABEN = $p->CATABEN;
			$teso->FORMAP = $p->FORMAP;
			$teso->RFCCTAORD = $p->RFCCTAORD;
			$teso->BANCOORDEXT = $p->BANCOORDEXT;
			$teso->CTAORD = $p->CTAORD;
			$teso->FECHAPAG = $p->FECHAPAG;
			$teso->timbrado = "0";
            $teso->id_et = $p->id_ar;
			$teso->save();
    	}
        DB::table("excel_tesoreria")
        ->where("integrado", "=", 3)
        ->update([
            "integrado" => 0
        ]);

    	$mostrar = DB::table('temporal_tesoreria')
        ->where('usuario', '=', Session::get('user'))
        ->count();

        if($mostrar > 0){
            $mostrar2 = DB::table('temporal_tesoreria')
            ->where('usuario', '=', Session::get('user'))
            ->delete();

            return response()->json($mostrar);
        }
        else{
            DB::table("excel_tesoreria")
            ->where("timbrado", "=", 3)
            ->delete();

            return response()->json([
                "respuesta" => "2"
            ]);
        }
    }

    public function borrarPrueba(Request $request)
    {
    	$mostrar = DB::table('temporal_tesoreria')
    	->where('usuario', '=', Session::get('user'))
    	->delete();

        DB::table("excel_tesoreria")
        ->where("timbrado", "=", 3)
        ->where("correo", "=", Session::get("user"))
        ->delete();

        /*$mostrar = DB::table('tesoreria')
        ->delete();*/

    	return response()->json([
    		"respuesta" => "si"
    	]);
    }
}
