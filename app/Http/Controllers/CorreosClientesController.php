<?php

namespace App\Http\Controllers;

use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
use App\Liquidadas_Model;
use App\Bancos_SAP_Model;
use App\Pagos;
use App\Facturas;
use App\Parcialidades;
use App\Clientes;
use App\Covestro_Model;
use App\Incidencias;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class CorreosClientesController extends Controller
{
    public function index()
    {
    	$clientes = DB::table('clientes')
    	->get();

    	return view('administradorDeCorreos', ["clientes" => $clientes]);
    }

    public function buscarClientes(Request $request)
    {
    	$sentencia = "";

    	if($request->has("id_cliente")){
    		if($request->get("id_cliente") != ""){
    			$sentencia = "id_cliente like '%".$request->get("id_cliente")."%'";
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="or (rfc_c like '%".$request->get("cliente")."%' or nombre_c like '%".$request->get("cliente")."%')";
    			}
    		}
    		else{
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="rfc_c like '%".$request->get("cliente")."%' or nombre_c like '%".$request->get("cliente")."%'";
    			}
    		}
    	}
    	else{
    		if($request->has("cliente") && $request->get("cliente") != ""){
    			$sentencia.="rfc_c like '%".$request->get("cliente")."%' or nombre_c like '%".$request->get("cliente")."%'";
    		}
    	}

    	if($sentencia == ""){
    		return response()->json([
    			"respuesta" => 0
    		]);
    	}
    	else{
    		$clientes = DB::table("clientes")
	    	->whereRaw(DB::raw($sentencia))
	    	->get();

	    	return response()->json($clientes);
    	}
    }

    public function seleccionarCliente(Request $request)
    {
    	$correos = DB::table("clientes as c")
    	->join("correos_clientes as cc", "c.id_cliente", "=", "cc.id_cliente")
	    ->where("c.id_cliente", "=", $request->get("id_cliente"))
	    ->orderBy("cc.correo", "asc")
	    ->get();

	    return response()->json($correos);
    }

    public function agregarCorreos(Request $request)
    {
    	
    	$procesoNuevo = DB::table("correos_clientes")->insertGetId([
    		"correo" => $request->get("nuevoCorreo"),
    		"id_cliente" => $request->get("idCliente")
    	]);

    	$correos = DB::table("clientes as c")
    	->join("correos_clientes as cc", "c.id_cliente", "=", "cc.id_cliente")
	    ->where("c.id_cliente", "=", $request->get("idCliente"))
	    ->orderBy("cc.correo", "asc")
	    ->get();

	    return response()->json($correos);
    }

    public function eliminarCorreos(Request $request)
    {
		try {
			$borrar = DB::table("correos_clientes")
			->where("id", "=", $request->get("id_cor"))
			->delete();

			if($borrar){
				$correos = DB::table("clientes as c")
				->join("correos_clientes as cc", "c.id_cliente", "=", "cc.id_cliente")
				->where("c.id_cliente", "=", $request->get("id_cliente"))
				->orderBy("cc.correo", "asc")
				->get();

				return response()->json($correos);
			}
			else{
				return response()->json([
					"respuesta" => 2
				]);
			}
		}catch (\Exception $e) {
			return response()->json([
				"respuesta" => 2,
				"mensaje" => $e->getMessage()
			]);
		}
    }
}
