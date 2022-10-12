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

class ReporteComplementosController extends Controller
{
    public function index()
    {
    	$datos = DB::table("complemento as c")
    	->join("archivos as a", "c.clearing_document","=", "a.clearing")
    	->get();

    	return view("reporteComplementos", ["datos"=>$datos]);
    }

    public function buscar(Request $request)
    {
    	$sentencia = "";

    	if($request->has("fecha_inicio") && $request->has("fecha_final")){
    		if($request->get("fecha_inicio") != "" && $request->get("fecha_final") != ""){
    			$sentencia .= "c.fechabus between '".$request->get("fecha_inicio")."' and '".$request->get("fecha_final")."'";
    			if($request->has("Acliente") && $request->get("Acliente") != ""){
    				$sentencia .= " and (c.id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."')";
    			}
    		}
    		else{
    			if($request->has("Acliente") && $request->get("Acliente") != ""){
    				$sentencia .= "c.id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
    			}
    		}
    	}
    	else{
    		if($request->has("Acliente") && $request->get("Acliente") != ""){
    			$sentencia .= "c.id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
    		}
    	}

    	if($sentencia == ""){
    		return response()->json([
    			"respuesta"=>0
    		]);
    	}
    	else{
    		$datos = DB::table("complemento as c")
    		->join("archivos as a", "c.clearing_document", "=", "a.clearing")
    		->whereRaw(DB::raw($sentencia))
    		->get();

    		return response()->json($datos);
    	}
    }
}

