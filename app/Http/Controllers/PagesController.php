<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
//use App\Http\Request;

class PagesController extends Controller
{

  public function clientManager(){
      return view('clientManager');
    }
  public function gestorDeClientes(){
    return view('Administrador.gestorDeClientes');
  }
  public function integradorDeArchivos(){
    return view('Administrador.integradorDeArchivos');
  }
  public function validacionAdministrador(){
    return view('Administrador.validacionAdministrador');
  }
  public function layoutTesoreriaBancos(){
    return view('Tesoreria.layoutTesoreriaBancos');
  }
  public function layoutComplementoRecepcionPago(){
    return view('complementoRecepcionPago');
  }
  public function layoutComplementoRPTabla(){
    return view('complementoRPTabla');
  }



  public function sap(){
    DB::table("bancos_p_SAP")
            ->truncate();

            $layouts = DB::table("bancos_l_SAP")
            ->get();
            if(Session::has("LayoutSAP")){
              Session::forget("LayoutSAP");
            }
            return view('SAT.layoutSAT', ["layout" => $layouts]);
  }
  public function credito(){
    DB::table("bancos_p_credito")
            ->truncate();
    $layouts = DB::table("bancos_l_credito")
            ->get();
            return view('Credito.layoutCredito', ["layout" => $layouts]);
  }
    public function tesoreria(){
      DB::table("bancos_p_tesoreria")
            ->truncate();

            $layouts = DB::table("bancos_l_tesoreria")
            ->get();
            return view('Tesoreria.layoutTesoreriaBancos', ["layout" => $layouts]);
    }
  public function complementoPagos(){
    return view('complementoPagos');
  }
  public function validacionSAT(){
    return view('SAT.validacionSAT');
  }
  public function validacionCredito(){
    return view('Credito.validacionCredito');
  }
    public function validacionTesoreria(){
      return view('Tesoreria.validacionTesoreria');
    }
    public function ComplementoRecepcionPago(){
      DB::table("temporal_SAP")
			->delete();
      $layouts = DB::table('excel_tesoreria')
        ->orderBy('fecha')
        ->take(5)
        ->get();
      return view('complementoRecepcionPago', ["layout" => $layouts]);
    }
    public function ComplementoRPTabla(){
      $layouts = DB::table('excel_tesoreria')
        // ->orderBy('fecha')
        // ->take(5)
        ->get();
      return view('complementoRPTabla', ["layout" => $layouts]);
    }

    /*Administrador de organizacion*/
    public function gestorempresa(){
      $covestro = DB::table("covestro")
      ->first();
      return view('gestorDatosEmpresa', ["covestro" => $covestro]);
    }
    public function gestorUsuarios(){
      $usuarios = DB::table('usuarios')->get();

      return view('Administrador.GestorUsuarios',["usuarios"=>$usuarios]);
    }
    public function correccionDeIncidencias(){
      return view('correccionDeIncidencias');
    }
    public function layoutSAT(){
      return view('SAT.layoutSAT');
    }
    public function createlay(){
      return view('crealayout');
    }
    public function pagolay(){
      return view('layoutformapago');
    }
    public function login(){
      return view('login');
    }
    public function logout(){
      Session::flush();
      return redirect()->action('PagesController@login');
    }
    public function layoutCredito(){
      return view('Credito.layoutCredito');
    }
    public function layoutgestbanco(){
      return view('layoutgestbanco');
    }
    public function inicio(Request $request){
        switch(Session::get("tipo")){
          case 1:
            $layouts = DB::table("bancos_l_SAP")
            ->get();
            //return redirect()->action('ArchviosController@index');
            return redirect()->action('EmpresaController@index');
            break;

          case 2:
            DB::table("bancos_p_tesoreria")
            ->truncate();

            $layouts = DB::table("bancos_l_tesoreria")
            ->get();
            return redirect()->action('ValidacionTesoreriaController@index');
            break;

          case 3:
          DB::table("bancos_p_credito")
            ->truncate();
            $layouts = DB::table("bancos_l_credito")
            ->get();
            return redirect()->action('ValidacionCreditoController@index');
            break;

          case 4:
            DB::table("bancos_p_SAP")
            ->truncate();

            $layouts = DB::table("bancos_l_SAP")
            ->get();
            if(Session::has("LayoutSAP")){
              Session::forget("LayoutSAP");
            }
            return redirect()->action('ValidacionSAPController@index');
            break;

          case 5:
            return redirect()->action('ResponsablesController@index');
            break;

        }
    }

    public function loggear(Request $request){
      /*$rol = "";
      $adServer = "LDAP://ad.glpoly.net";
      $ldap = ldap_connect($adServer);
      $ldaprdn = 'ad' . "\\" . $request->get('email');
      
      ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
      $bind = @ldap_bind($ldap, $ldaprdn, $request->get('contrasenia'));                      
      
      if ($bind){*/
        
        $usuarios = DB::table('usuarios')
        ->where('cwid', '=', $request->get('email'))
        ->count();
        if($usuarios == 1){
          $usuario = DB::table('usuarios')
          ->where('cwid', '=', $request->get('email'))
          ->get();
           
          session()->put('user', $usuario[0]->correo);
          session()->put('tipo', $usuario[0]->tipo);
          session()->put('resp', $usuario[0]->responsable);
          session()->put('proceso', 0);
          return response()->json([
            "respuesta" => "si"
          ]);

        }
        else{
          return response()->json([
            "respuesta" => "no"
          ]);
        }
      /*}
      else{
        return response()->json([
          "respuesta" => "no"
        ]);
      }*/
    }
    public function index(){
      $datos = DB::select("select * from usuarios",[1]);
      return view('login.index',['datos'=>$datos]);

      foreach ($datos as $datos) {
        echo $datos -> datos;
      }
      return view('login');
    }

    public function validacion()
    {
      $layout = "";
      switch(Session::get("tipo")){
        case 2:
          $layout = DB::table('bancos_l_tesoreria')
          ->get();
          Session::put('usua', "Tesorería");
          return view('Tesoreria.validacionTesoreria', ["layout"=>$layout]);
          break;

        case 3:
          $layout = DB::table('bancos_l_credito')
          ->get();
          Session::put('usua', "Crédito y Cobranza");
          return view('Credito.validacionCredito', ["layout"=>$layout]);
          break;

        case 4:
          $layout = DB::table('bancos_l_SAP')
          ->get();
          Session::put('usua', "SAP");
          return view('SAT.validacionSAT', ["layout"=>$layout]);
          break;
        }
    }
}
