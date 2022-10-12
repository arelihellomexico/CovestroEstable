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

use Barryvdh\DomPDF\Facade as PDF;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ReportePagoController extends Controller
{

    public function  index(){
        $datos=DB::table("complemento")
        ->get();

    
        return view("reportePago", ["datos"=>$datos]);

    }


    public function buscar(Request $request)
    {
        $sentencia="";  
        
          
            if($request->has("fecha_inicio") && $request->has("fecha_final")){
                if($request->get("fecha_inicio") && $request->get("fecha_final") != ""){
                    $sentencia = "fechabus between '".$request->get("fecha_inicio")."' and '".$request->get("fecha_final")."' ";
                    if($request->has("Acliente") && $request->get("Acliente") != ""){
                       $sentencia .= " and (id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."')";
                    }
                }

               else{
                 if($request->has("Acliente") && $request->get("Acliente") != ""){
                        $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                    }
                }
               
            }
        
            else{
                if($request->has("Acliente") && $request->get("Acliente")!=""){
                    $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                }
            }
            if($sentencia !=""){
                $datos=DB::table("complemento")
                ->whereRaw(DB::raw($sentencia))
                ->get();

                return response()->json($datos);   
            }
        
    }
    public function descargarExcel(Request $request)
    {
            $sentencia="";  
          
            if($request->has("fecha_inicio") && $request->has("fecha_final")){
                if($request->get("fecha_inicio") && $request->get("fecha_final") != ""){
                    $sentencia = "fechabus between '".$request->get("fecha_inicio")."' and '".$request->get("fecha_final")."' ";
                    if($request->has("Acliente") && $request->get("Acliente") != ""){
                       $sentencia .= " and (id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."')";
                    }
                }

               else{
                 if($request->has("Acliente") && $request->get("Acliente") != ""){
                        $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                    }
                }
               
            }
        
            else{
                if($request->has("Acliente") && $request->get("Acliente")!=""){
                    $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                }
            }
            if($sentencia != ""){
                $datos=DB::table("complemento")
                ->whereRaw(DB::raw($sentencia))
                ->get();

                Excel::create("Reporte_de_pagos", function ($excel) use ($datos) {
                    $excel->setTitle("Title");
                    $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                        $sheet->row(1, [
                            'ID del cliente', 'Cliente', 'RFC', 'Clearing', 'Forma de Pago', 'Fecha de pago', 'Monto del pago', 'Moneda de Pago', 'Tipo de cambio', 'Cuenta del Beneficiario', 'Banco del Ordenante', 'Cuenta del Ordenante'
                        ]);
                        foreach ($datos as $indice => $pago) {
                            $sheet->row($indice+2, [
                                $pago->id_cliente, $pago->nombre_c, $pago->rfc_c, $pago->clearing_document, $pago->formap, $pago->fechap, $pago->montoP, $pago->monedaP, $pago->tipocambioP, $pago->cataben, $pago->bancoordext, $pago->ctaord
                            ]);
                        }
                    });
                })->download('xls');
                return back();
            }
            else{
                $datos = Complemento::all();            

                Excel::create("Reporte_de_pagos", function ($excel) use ($datos) {
                    $excel->setTitle("Title");
                    $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                        $sheet->row(1, [
                            'ID del cliente', 'Cliente', 'RFC', 'Clearing', 'Forma de Pago', 'Fecha de pago', 'Monto del pago', 'Moneda de Pago', 'Tipo de cambio', 'Cuenta del Beneficiario', 'Banco del Ordenante', 'Cuenta del Ordenante'
                        ]);
                        foreach ($datos as $indice => $pago) {
                            $sheet->row($indice+2, [
                                $pago->id_cliente, $pago->nombre_c, $pago->rfc_c, $pago->clearing_document, $pago->formap, $pago->fechap, $pago->montoP, $pago->monedaP, $pago->tipocambioP, $pago->cataben, $pago->bancoordext, $pago->ctaord
                            ]);
                        }
                    });
                })->download('xls');
                return back();
            }
    }

    public function descargarPDF(Request $request)
    {
            $sentencia="";  
          
            if($request->has("fecha_inicio") && $request->has("fecha_final")){
                if($request->get("fecha_inicio") && $request->get("fecha_final") != ""){
                    $sentencia = "fechabus between '".$request->get("fecha_inicio")."' and '".$request->get("fecha_final")."' ";
                    if($request->has("Acliente") && $request->get("Acliente") != ""){
                       $sentencia .= " and (id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."')";
                    }
                }

               else{
                 if($request->has("Acliente") && $request->get("Acliente") != ""){
                        $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                    }
                }
               
            }
        
            else{
                if($request->has("Acliente") && $request->get("Acliente")!=""){
                    $sentencia .= " id_cliente = ".$request->get("Acliente")." or clearing_document = '".$request->get("Acliente")."'";
                }
            }
            if($sentencia == ""){
                $datos = Complemento::all(); 
                $pdf = PDF::loadView('reportePagosPDF', compact('datos'))->setPaper('a4', 'landscape');;

                return $pdf->download('Reporte_de_Pagos.pdf');
            }
            else{
                $datos=DB::table("complemento")
                ->whereRaw(DB::raw($sentencia))
                ->get();

                if($datos){
                    $pdf = PDF::loadView('reportePagosPDF', compact('datos'))->setPaper('a4', 'landscape');;

                    return $pdf->download('Reporte_de_Pagos.pdf');
                }
                else{
                    return response()->json([
                        "respuesta" => 1
                    ]);
                }
            }
    }
}




