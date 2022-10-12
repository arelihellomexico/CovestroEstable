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
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Http\Request;

 
class ReporteParcialidadesController extends Controller
{

  public function index()
  {

    $datos = DB::table("parcialidades as p ")
      ->join("complemento as c", "p.clearing_document", "=", "c.clearing_document")
      ->get();

    return view("reportesParcialidades", ["datos" => $datos]);
  }



  public function buscar(Request $request)
  {
    $sentencia = "";
  //<--filtro de fecha inicio,fin y clearing-->
    if ($request->has("fechainicio") && $request->has("fechafin")) {
      if ($request->get("fechainicio") && $request->get("fechafin") != "") {
        $sentencia = "fechabus between '" . $request->get("fechainicio") . "' and '" . $request->get("fechafin") . "' ";
        if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
          $sentencia .= "and (id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
        }
      } else {
        if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
          $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
        }
      }
    }
    else{
      if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
        $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
      }
    }
    if ($sentencia != "") {
        $datos = DB::table("parcialidades as p ")
        ->join("complemento as c", "p.clearing_document", "=", "c.clearing_document")
        ->whereRaw(DB::raw($sentencia))
        ->get();

        return response()->json($datos); 
    }
  }

  public function descargarPar(Request $request)
  {
      $sentencia = "";
  //<--filtro de fecha inicio,fin y clearing-->
    if ($request->has("fechainicio") && $request->has("fechafin")) {
      if ($request->get("fechainicio") && $request->get("fechafin") != "") {
        $sentencia = "fechabus between '" . $request->get("fechainicio") . "' and '" . $request->get("fechafin") . "' ";
        if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
          $sentencia .= "and (id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
        }
      } else {
        if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
          $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
        }
      }
    }
    else{
      if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
        $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
      }
    }


      if($sentencia == ""){
        
        $datos = Facturas::all();
            Excel::create("Reporte_Impuestos_".date("Y-m-d"), function ($excel) use ($datos) {
              $est="";
                $excel->setTitle("Title");
                $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                    $sheet->row(1, [
                  'ID_CLIENTE' ,'CLIENTE','CLEARING','FOLIO FACTURA','PARCIALIDAD' ,'SALDO ANTERIOR','IMPORTE PAGADO','SALDO INSOLUTO','TIPO DE CAMBIO','MONEDA','ESTATUS'
                    ]);
                    foreach ($datos as $indice => $pago) {
                      if($pago->impsaldoins==0){
                        $est= 'Pagado';
                      }else{
                        $est='No liquidado';
                      }

                        $sheet->row($indice+2, [
                            $pago->id_cliente,$pago->nombre_c, $pago->clearing_document, $pago->folio, $pago->numparcialidad, $pago->impsaldoant, $pago->imppagado, $pago->impsaldoins, $pago->tipcambio, $pago->moneda,$est           
                        ]);
                }
                });
            })->download('xls');
            return back();
    }
    else{
        $datos = DB::table("parcialidades as p ")
        ->join("complemento as c", "p.clearing_document", "=", "c.clearing_document")
        ->whereRaw(DB::raw($sentencia))
        ->get();
        if($datos){
            Excel::create("Reporte_Impuestos_".date("Y-m-d"), function ($excel) use ($datos) {

                $excel->setTitle("Title");
                $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                    $sheet->row(1, [
                      'ID_CLIENTE' ,'CLIENTE','CLEARING','FOLIO FACTURA','PARCIALIDAD' ,'SALDO ANTERIOR','IMPORTE PAGADO','SALDO INSOLUTO','TIPO DE CAMBIO','MONEDA','ESTATUS'                  
                            ]);
                    foreach ($datos as $indice => $pago) {
                      if($pago->impsaldoins==0){
                        $est= 'Pagado';
                      }else{
                        $est='No liquidado';      
                    }

                    $sheet->row($indice+2, [
                          $pago->id_cliente,$pago->nombre_c, $pago->clearing_document, $pago->folio, $pago->numparcialidad, $pago->impsaldoant, $pago->imppagado, $pago->impsaldoins, $pago->tipcambio, $pago->moneda,$est
                         ]);
                }
              });
            })->download('xls');
            return back();
        }
        else{
            return response()->json([
                "respuesta" => 1
            ]);
        }
   }
   
}



  public function descargarPDF(Request $request)
  {
      $sentencia = "";
    //<--filtro de fecha inicio,fin y clearing-->
      if ($request->has("fechainicio") && $request->has("fechafin")) {
        if ($request->get("fechainicio") && $request->get("fechafin") != "") {
          $sentencia = "fecha between '" . $request->get("fechainicio") . "' and '" . $request->get("fechafin") . "' ";
          if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
            $sentencia .= "and (id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
          }
        } else {
          if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
            $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
          }
        }
      }
      else{
        if ($request->has("Ncliente") && $request->get("Ncliente") != "") {
          $sentencia .= "(id_cliente like '%" . $request->get("Ncliente") . "%' or p.clearing_document like '%" . $request->get("Ncliente") . "%')";
        }
      }

      if($sentencia == ""){
        $datos = Parcialidades::all();
        $pdf = PDF::loadView('reporteParcialidadesPDF', compact('datos'))->setPaper([0,0,720.00,1440.00], 'landscape');

        return $pdf->download('Reporte_de_Parcialidades.pdf');
    }
    else{
      $datos = DB::table("parcialidades as p ")
      ->join("complemento as c", "p.clearing_document", "=", "c.clearing_document")
      ->whereRaw(DB::raw($sentencia))
      ->get();

        if($datos){
            $pdf = PDF::loadView('reporteParcialidadesPDF', compact('datos'))->setPaper([0,0,720.00,1440.00], 'landscape');;

            return $pdf->download('Reporte_de_Parcialidades.pdf');
        }
        else{
            return response()->json([
                "respuesta" => 1
            ]);
        }

    
  }
}



}


  




  



 


