<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Covestro_Model;
use App\Cuentas_Model;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index(){
      $datos = DB::table('covestro')
      ->first();

      $cuentas = DB::table('cuentas')
      ->get();

      return view('gestorDatosEmpresa', ['datos'=>$datos,'cuentas'=>$cuentas]);
    }

    public function agregarBanco(Request $request){
      $bancos = new Cuentas_Model;

      $bancos->numcuenta=$request->get('numcuenta');
      $bancos->nombrebanco=$request->get('nombre_banco');
      $bancos->RFC_Banco=$request->get('rfc_banco');
      $bancos->cuenta_clabe=$request->get('cuenta_clabe');

      if($bancos->save()){
        $bancos = DB::table('cuentas')
        ->get();

        return response()->json($bancos);
      }else{
        return response()->json(['respuesta'=>'no']);
      }
    }

    public function actualizarDatos(Request $request){

      $usar = 0;

      if($request->has("usar_credito")){
        $usar = 1;
      }

      $datos = DB::table("covestro")
      ->where('rfc_e', '=', 'MNM150227D32')
      ->update([
      'nombre_e' => $request -> get('nombre_e'),
      'calle_e' => $request -> get('calle_r'),
      'numext_e' => $request -> get('next_e'),
      'numint_e' => $request -> get('nint_e'),
      'colonia_e' => $request -> get('colonia_e'),
      'cpostal_e' => $request -> get('cp_e'),
      'localidad_e' => $request ->get('localidad_r'),
      'referencia_e' => $request ->get('ref_e'),
      'municipio_e' => $request ->get('municip_e'),
      'estado_e' => $request ->get('estado_e'),
      'pais_e' => $request ->get('pais_e'),
      'regimen' => $request ->get('regimen'),
      'numpago' => $request ->get('numpago'),
      'version_complemento' => $request ->get('version_complemento'),
      'version_fiscal' => $request ->get('version_fiscal'),
      'metpago' => $request ->get('metpago'),
      "usar_credito" => $usar,
    ]);

      if($datos){
        return response()->json(['respuesta'=>'si']);
      }else{
        return response()->json(['respuesta'=>'no']);
      }
    }

    public function editarBanco(Request $request){
      $banco = DB::table('cuentas')
      ->where('numcuenta','=',$request ->get('numcuenta')) ->first();

      return response()->json($banco);
    }

    public function actualizarBancos(Request $request){
      $banco = DB::table("cuentas")
     ->where('numcuenta', '=', $request->get('numcuenta'))
     ->update([
     'nombrebanco' => $request -> get('nombre_banco'),
     'RFC_Banco' => $request -> get('rfc_banco'),
     'cuenta_clabe' => $request -> get('cuenta_clabe'),
    ]);

    if($banco){
      $bancos = DB::table('cuentas')
      ->get();

      return response()->json($bancos);
    }else{
      return response()->json(['respuesta'=>'no']);
    }

    }
    public function eliminarBanco(Request $request){
      $banco = DB::table('cuentas')
      ->where('numcuenta','=',$request ->get('numcuenta'))
      ->delete();

      if($banco){
        $bancos = DB::table('cuentas')
        ->get();

        return response()->json($bancos);
      }else{
        return response()->json(['respuesta'=>'no']);
      }

    }
}
