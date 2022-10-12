<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\GestorClientes_Model;

class GestorClientesController extends Controller
{
    public function index(){
      $datos = DB::table('clientes')->get();

      return view('gestorDeClientes',['datos'=>$datos]);
    }
}
