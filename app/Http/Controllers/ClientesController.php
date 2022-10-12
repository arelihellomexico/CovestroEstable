<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\Correos;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
	{
		$clientes = DB::table("clientes")
		->get();
		return view('clientManager', ["clientes" => $clientes]);
	}
    public function guardar(Request $request)
    {
    	Excel::load($request->excel, function($reader){
    		foreach ($reader->get() as $key => $row){

                $datos = DB::table("clientes")
                ->where("id_cliente", "=", $row['id'])
                ->count();

                if($datos > 0){
                    $datos = DB::table("clientes")
                    ->where("id_cliente", "=", $row['id'])
                    ->delete(); 
                }

                $cliente = new Clientes;
                $cliente->id_cliente = $row['id'];
                $cliente->rfc_c = str_replace(" ", "", $row['rfc_c']);
                $cliente->nombre_c = $row['cliente'];
                $cliente->nombre2_c = $row['cliente2'];
                $cliente->telefono_c = $row['telefono'];
                $cliente->direccion_c = $row['calle'];
                $cliente->cpostal_c = $row['cp'];
                $cliente->localidad_c = $row['distrito'];
                $cliente->municipio_c = $row['ciudad'];
                $cliente->estado_c = $row['region'];
                $cliente->pais_c = $row['cty'];
                $cliente->residenciafiscal = $row['cty'];
                if($row['cty'] != "MX"){
                    $cliente->numregidtrib = $row['rfc_c'];
                }
                $cliente->responsable = $row['responsable'];
                $cliente->save();
            /*foreach ($reader->get() as $key => $row){
                $correos = str_replace(",", ";", $row['correo']);
                $correos = explode(";", $correos);
                foreach ($correos as $c) {
                    if($c != "" && $c != " "){
                        $cliente = new Correos;
                        $cliente->correo = $c;
                        $cliente->id_cliente = $row['cliente'];
                        $cliente->save();
                    }
                }

                $cliente = new Correos;
                $cliente->correo = $row['responsable'];
                $cliente->id_cliente = $row['cliente'];
                $cliente->save();

                DB::table("clientes")
                ->where("id_cliente", "=", $row['cliente'])
                ->update([
                    "responsable" => $row['responsable']
                ]);*/
            }
    	});

        $clientes = DB::table('clientes')
        ->get();

        return response()->json($clientes);
    }

    public function show($id)
    {
    	$cliente = Clientes::findOrFail($id);
		return view('Clientes.show', ["cliente" => $cliente]);
    }

    public function edit($id)
    {
    	$cliente = Clientes::findOrFail($id);
		return view('Clientes.edit', ["cliente" => $cliente]);
    }

    public function actualizar(Request $request)
    {
    	$cliente = Empresa::findOrFail($id);
    	$cliente->rfc_c = $request->get('rfc_c');
    	$cliente->nombre_c = $request->get('nombre_c');
    	$cliente->calle_c = $request->get('calle_c');
    	$cliente->numext_c = $request->get('numext_c');
    	$cliente->numint_c = $request->get('numint_c');
    	$cliente->colonia_c = $request->get('colonia_c');
    	$cliente->cpostal_c = $request->get('cpostal_c');
    	$cliente->localidad_c = $request->get('localidad_c');
    	$cliente->referencia_c = $request->get('referencia_c');
    	$cliente->municipio_c = $request->get('municipio_c');
    	$cliente->estado_c = $request->get('estado_c');
    	$cliente->pais_c = $request->get('pais_c');

    	if($cliente->update()){

    	}
    	else{

    	}
    }

    public function destroy($id)
    {
    	$cliente = Empresa::findOrFail($id);
    	if($cliente->delete()){

    	}
    	else{

    	}
    }
}
