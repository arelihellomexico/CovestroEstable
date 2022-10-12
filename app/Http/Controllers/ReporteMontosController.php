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

class ReporteMontosController extends Controller
{
    public function index()
    {
    	$datos = DB::table("complemento")
    	->get();

    	return view("reporteMontos", ["datos"=>$datos]);
    }

    public function buscar(Request $request)
    {
    	$sentencia = "";

    	if($request->has("fechaInicio") && $request->has("fechaFin")){
    		if($request->get("fechaInicio") != "" && $request->get("fechaFin") != ""){
    			$sentencia .= "fechabus between '".$request->get("fechaInicio")."' and '".$request->get("fechaFin")."'";
    			if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    				$sentencia .= " and id_cliente = ".$request->get("id_cliente");
    			}
    		}
    		else{
    			if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    				$sentencia .= "id_cliente = ".$request->get("id_cliente");
    			}
    		}
    	}
    	else{
    		if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    			$sentencia .= "id_cliente = ".$request->get("id_cliente");
    		}
    	}

    	if($sentencia == ""){
    		return response()->json([
    			"respuesta"=>0
    		]);
    	}
    	else{
    		$datos = DB::table("complemento")
    		->whereRaw(DB::raw($sentencia))
    		->get();

            return response()->json($datos);
    	}
    }
}
