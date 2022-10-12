<?php

namespace App\Http\Controllers;

use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
use App\Pagos;
use App\Facturas;
use App\Parcialidades;
use App\Complemento;
use App\Incidencias;
use App\Similaridades;
use App\Covestro_Model;
use App\Archivos;
use App\Incidencias_SAP_Model;
use App\Incidencias_Tesoreria_Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ProcesosController extends Controller
{
	public function index()
	{
		$proceso = DB::table("procesos")
		->where("integracion", "=", 0)
		->max("id_pro");

	    $procesos = DB::table("procesos")
	    ->where("integracion", "=", 0)
	    ->get();

	    $archivos = DB::table("archivos")
	    ->where('id_pro', '=', $proceso)
	    ->get();

	    return view("traficoDeProcesos", ["procesos"=>$procesos, "archivos"=>$archivos, "proceso"=>$proceso]);
	}

	public function mostrarProceso(Request $request)
	{
		$archivos = DB::table("archivos")
	    ->where('id_pro', '=', $request->get("id_pro"))
	    ->get();

	    return response()->json($archivos);
	}

	public function buscarProcesos(Request $request)
	{
		$procesos = DB::table("procesos")
		->whereBetween('fecha', [$request->get('inicioPro'), $request->get('finPro')])
	    ->get();

	    return response()->json($procesos);
	}
	public function buscarArchivos(Request $request)
	{
		$sentencia = "";

    	if($request->has("inicio") && $request->has("fin")){
    		if($request->get("inicio") != "" && $request->get("fin") != ""){
    			$sentencia = "fecha bewteen '".$request->get("inicio")."' and '".$request->get("fin")."' ";
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="and (rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%')";
    			}
    		}
    		else{
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%'";
    			}
    		}
    	}
    	else{
    		if($request->has("cliente") && $request->get("cliente") != ""){
    			$sentencia.="rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%'";
    		}
    	}

    	if($sentencia == ""){
    		return response()->json([
    			"respuesta" => 0
    		]);
    	}
    	else{
    		$archivos = DB::table("archivos")
	    	->whereRaw(DB::raw($sentencia))
	    	->get();

	    	return response()->json($archivos);
    	}
	}

	public function actualizarStatus(Request $request)
	{
		$proceso = $request->get("id_pro");
		$correctos = 0;
		$incorrectos = 0;

		$archivos = DB::table("archivos")
	    ->get();

	    foreach ($archivos as $archivo) {
	    	if($request->has("id_ar".$archivo->id_ar)){
	    		DB::table("archivos")
				->where("id_ar", "=", $archivo->id_ar)
				->update([
				   	"timbrado" => $request->get("id_ar".$archivo->id_ar)
				]);
				if($request->get("id_ar".$archivo->id_ar) == 1){
					$correctos++;
				}
				elseif($request->get("id_ar".$archivo->id_ar) == 2){
					$incorrectos++;
				}
	    	}
	    }

	    DB::table("procesos")
			->where("id_pro", "=", $proceso)
			->update([
		    	"correctos" => $correctos,
		    	"erroneos" => $incorrectos
		    ]);

	    $proceso = DB::table("procesos")
		->max("id_pro");

	    return response()->json($proceso);
	}

	public function finalizarProceso()
	{
		 $proceso = DB::table("procesos")
		->max("id_pro");

		//$cuantos = DB::table("archivos")
		//->where("id_pro", "=", $proceso)
		//->where("timbrado", "=", 0)
		//->count();

		//if($cuantos < 1){
			DB::table("procesos")
			->where("id_pro", "=", $proceso)
			->update([
		    	"integracion" => 0,
		    	"timbrado" => 0,
		    	"obtencion" => 0
		    ]);

			Session::put("proceso", 0);

			return redirect()->action('ArchviosController@index');
		//}
		//else{
			//return response()->json([
				//"respuesta" => 2
			//]);
		//}
	}
}
