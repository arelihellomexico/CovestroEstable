<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Usuarios;

class UsuariosController extends Controller
{
    public function index(){
      $usuarios = DB::table('usuarios')->get();

      return view('Administrador.GestorUsuarios',["usuarios"=>$usuarios]);
    }

    public function agregarUsuario(Request $request){
      
        $usuario = new Usuarios;

        $usuario->correo=$request->get('correo');
        $usuario->nombre=$request->get('nombre');
        $usuario->cwid=$request->get('usuario');
        $usuario->tipo=$request->get('rol');
        if($request->has("responsable")){
          $usuario->responsable=1;
        }
        else{
          $usuario->responsable=0;
        }

        if($usuario->save()){
          $usuarios = DB::table('usuarios')->get();
          return response()->json($usuarios);
        }else{
          return response()->json(["respuesta" => "2"]);
        }
    }

    public function eliminarUsuario(Request $request){
      $usuario = DB::table('usuarios')
      ->where('cwid','=',$request ->get('correo'))
      ->delete();

      if($usuario){
        $usuarios = DB::table('usuarios')->get();
        return response()->json($usuarios);
      }else{
        return response()->json(["respuesta" => "2"]);
      }

    }

    public function editarUsuario(Request $request){
      $usuario = DB::table('usuarios')
      ->where('cwid','=',$request ->get('correo')) ->first();

      return response()->json($usuario);
    }

    public function actualizarUsuario(Request $request){
      $resp = 0;
      if($request->has("responsable")){
          $resp=1;
        }
        else{
          $resp=0;
        }
      $usuario = DB::table("usuarios")
     ->where('cwid', '=', $request->get('usuario'))
     ->update([
     'nombre' => $request -> get('nombre'),
     'correo' => $request -> get('correo'),
     'tipo' => $request -> get('rol'),
     'responsable' => $resp,
    ]);

    if($usuario){
    $usuarios = DB::table('usuarios')->get();
      return response()->json($usuarios);

    }else{
      return response()->json(["respuesta" => "2"]);
    }

    }
}
