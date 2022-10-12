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

class ResponsablesController extends Controller
{
    public function index()
    {
    	$archivos = DB::table("archivos")
        ->where("timbrado", '=', 1)
    	->get();

    	return view("responsablesClientes", ["archivos" => $archivos]);
    }

    public function buscar(Request $request)
    {
    	$sentencia = "";
        $prestatus = "";

        if($request->get("status") != 3){
            $prestatus = " and timbrado = "-$request->get("status");
        }

    	if($request->has("inicio") && $request->has("fin")){
    		if($request->get("inicio") != "" && $request->get("fin") != ""){
    			$sentencia = "fechabus between '".$request->get("inicio")."' and '".$request->get("fin")."' ";
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="and (rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%') ".$prestatus;
    			}
    		}
    		else{
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%' ".$prestatus;
    			}
    		}
    	}
    	else{
    		if($request->has("cliente") && $request->get("cliente") != ""){
    			$sentencia.="rfc_cliente like '%".$request->get("cliente")."%' or cliente like '%".$request->get("cliente")."%' ".$prestatus;
    		}
    	}

    	if($sentencia == ""){
    		if($prestatus == ""){
                $archivos = DB::table("archivos")
                ->get();

                return response()->json($archivos);
            }
            else{
                $archivos = DB::table("archivos")
                ->where("timbrado", "=", $request->get("status"))
                ->get();

                return response()->json($archivos);
            }
    	}
    	else{
    		$archivos = DB::table("archivos")
	    	->whereRaw(DB::raw($sentencia))
	    	->get();

	    	return response()->json($archivos);
    	}
    }
}
