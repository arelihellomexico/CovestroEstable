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

class ReporteImpuestosController extends Controller
{
    public function index(){
        /*$facs = [];
        $c = 0;*/
    	$datos = DB::table("complemento as c")
        ->join("parcialidades as p", "c.clearing_document", "=", "p.clearing_document")
    	->get();

        foreach ($datos as $d) {
            $residencia = "";
            $existeC = DB::table("clientes")
            ->where("id_cliente", "=", $d->id_cliente)
            ->count();
            if($existeC > 0){
                $existeC = DB::table("clientes")
                ->where("id_cliente", "=", $d->id_cliente)
                ->first();

                if($existeC->residenciafiscal == "MX"){
                    $residencia = "MEX";
                }
                else{
                    $residencia = $existeC->residenciafiscal;
                }
            }
            else{
                $residencia = "";
            }
            $totalImp = 0;
            $baseImp = 0;
            $imp = 0;
            $totalImp = (float)$d->tipo_cambio_bancos * (float)$d->imppagado;
            $baseImp = $totalImp / (float)("1.".$d->tipo_impuesto);
            $imp = $totalImp - $baseImp;

            $datos = DB::table("parcialidades")
            ->where("id_par", "=", $d->id_par)
            ->update([
                "base_impuesto" => $baseImp,
                "impuesto" => $imp,
                "total_impuesto" => $totalImp,
                "residencia" => $residencia
            ]);
        }

        $datos = DB::table("complemento as c")
        ->join("parcialidades as p", "c.clearing_document", "=", "p.clearing_document")
        ->get();

    	return view("reporteImpuestos", ["datos" => $datos]);
    }

    public function buscar(Request $request){
    	$sentencia = "";

    	if($request->has("fechaInicio") && $request->has("fechaFin")){
    		if($request->get("fechaInicio") != "" && $request->get("fechaFin") != ""){
    			$sentencia .= "fechabus between '".$request->get("fechaInicio")."' and '".$request->get("fechaFin")."'";
    			if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    				$sentencia .= " and (id_cliente = ".$request->get("id_cliente");
    				if($request->has("folio") && $request->get("folio") != ""){
    					$sentencia .= " or folio = '".$request->get("folio")."'";
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
    				else{
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
                    $sentencia.=")";
    			}
    			else{
    				if($request->has("folio") && $request->get("folio") != ""){
    					$sentencia .= "and (folio = '".$request->get("folio")."'";
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= " or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
                        $sentencia.=")";
    				}
    				else{
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "and (p.clearing_document like '%".$request->get("clearing")."%'";
                            $sentencia.=")";
    					}
    				}
    			}
                
    		}
    		else{
    			if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    				$sentencia .= "id_cliente = ".$request->get("id_cliente");
    				if($request->has("folio") && $request->get("folio") != ""){
    					$sentencia .= " or folio = '".$request->get("folio")."'";
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
    				else{
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
    			}
    			else{
    				if($request->has("folio") && $request->get("folio") != ""){
    					$sentencia .= "folio = '".$request->get("folio")."'";
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
    				else{
    					if($request->has("clearing") && $request->get("clearing")){
    						$sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
    					}
    				}
    			}
    		}
    	}
    	else{
    		if($request->has("id_cliente") && $request->get("id_cliente") != ""){
    			$sentencia .= "id_cliente = ".$request->get("id_cliente");
    			if($request->has("folio") && $request->get("folio") != ""){
    				$sentencia .= " or folio = '".$request->get("folio")."'";
    				if($request->has("clearing") && $request->get("clearing")){
    					$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    				}
    			}
    			else{
    				if($request->has("clearing") && $request->get("clearing")){
    					$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    				}
    			}
    		}
    		else{
    			if($request->has("folio") && $request->get("folio") != ""){
    				$sentencia .= "folio = '".$request->get("folio")."'";
    				if($request->has("clearing") && $request->get("clearing")){
    					$sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
    				}
    			}
    			else{
    				if($request->has("clearing") && $request->get("clearing")){
    					$sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
    				}
    			}
    		}
    	}

    	if($sentencia == ""){
    		return response()->json([
    			"respuesta" => 0
    		]);
    	}
    	else{
    	   $datos = DB::table("complemento as c")
            ->join("parcialidades as p", "c.clearing_document", "=", "p.clearing_document")
	    	->whereRaw(DB::raw($sentencia))
	    	->get();

	    	if($datos){
	    		return response()->json($datos);
	    	}
	    	else{
	    		return response()->json([
	    			"respuesta" => 1
	    		]);
	    	}
    	}
    }

    public function descargarExcel(Request $request){
        $sentencia = "";

        if($request->has("fechaInicio") && $request->has("fechaFin")){
            if($request->get("fechaInicio") != "" && $request->get("fechaFin") != ""){
                $sentencia .= "fechabus between '".$request->get("fechaInicio")."' and '".$request->get("fechaFin")."'";
                if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                    $sentencia .= " and (id_cliente = ".$request->get("id_cliente");
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= " or folio = '".$request->get("folio")."'";
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    $sentencia.=")";
                }
                else{
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= "and (folio = '".$request->get("folio")."'";
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= " or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                        $sentencia.=")";
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "and (p.clearing_document like '%".$request->get("clearing")."%'";
                            $sentencia.=")";
                        }
                    }
                }
                
            }
            else{
                if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                    $sentencia .= "id_cliente = ".$request->get("id_cliente");
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= " or folio = '".$request->get("folio")."'";
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                }
                else{
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= "folio = '".$request->get("folio")."'";
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                }
            }
        }
        else{
            if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                $sentencia .= "id_cliente = ".$request->get("id_cliente");
                if($request->has("folio") && $request->get("folio") != ""){
                    $sentencia .= " or folio = '".$request->get("folio")."'";
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
                else{
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
            }
            else{
                if($request->has("folio") && $request->get("folio") != ""){
                    $sentencia .= "folio = '".$request->get("folio")."'";
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
                else{
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
            }
        }

        if($sentencia == ""){
            $datos = DB::table("complemento as c")
            ->join("parcialidades as p", "c.clearing_document", "=", "p.clearing_document")
            ->get();

                Excel::create("Reporte_Impuestos_".date("Y-m-d"), function ($excel) use ($datos) {
                    $excel->setTitle("Title");
                    $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                        $sheet->row(1, [
                            'ID del cliente', 'Cliente', 'País', 'Clearing Document', 'Factura', 'Moneda', 'Tipo de cambio', 'Fecha', 'Parcialidad', 'Saldo Anterior', 'Importe Pagado', 'Saldo Insoluto', 'Tipo de Impuesto', 'Base para impuesto (MXN)', 'Impuesto (MXN)', 'Total (MXN)'
                        ]);
                        foreach ($datos as $indice => $pago) {
                            $sheet->row($indice+2, [
                                $pago->id_cliente, $pago->nombre_c, $pago->residencia, $pago->clearing_document, $pago->folio, $pago->moneda, $pago->tipo_cambio_bancos, $pago->fechabus, $pago->numparcialidad, $pago->impsaldoant, $pago->imppagado, $pago->impsaldoins, $pago->tipo_impuesto."%", $pago->base_impuesto, $pago->impuesto, $pago->total_impuesto
                            ]);
                        }
                    });
                })->download('xls');
                return back();
        }
        else{
            $datos = DB::table("complemento as c")
            ->join("parcialidades as p", "c.clearing_document", "=", "p.clearing_document")
            ->whereRaw(DB::raw($sentencia))
            ->get();

            if($datos){
                Excel::create("Reporte_Impuestos_".date("Y-m-d"), function ($excel) use ($datos) {
                    $excel->setTitle("Title");
                    $excel->sheet("Sheet 1", function ($sheet) use ($datos) {
                        $sheet->row(1, [
                            'ID del cliente', 'Cliente', 'País', 'Clearing Document', 'Factura', 'Moneda', 'Tipo de cambio', 'Fecha', 'Parcialidad', 'Saldo Anterior', 'Importe Pagado', 'Saldo Insoluto', 'Tipo de Impuesto', 'Base para impuesto (MXN)', 'Impuesto (MXN)', 'Total (MXN)'
                        ]);
                        foreach ($datos as $indice => $pago) {
                            $sheet->row($indice+2, [
                                $pago->id_cliente, $pago->nombre_c, $pago->residencia, $pago->clearing_document, $pago->folio, $pago->moneda, $pago->tipo_cambio_bancos, $pago->fechabus, $pago->numparcialidad, $pago->impsaldoant, $pago->imppagado, $pago->impsaldoins, $pago->tipo_impuesto."%", $pago->base_impuesto, $pago->impuesto, $pago->total_impuesto
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

        if($request->has("fechaInicio") && $request->has("fechaFin")){
            if($request->get("fechaInicio") != "" && $request->get("fechaFin") != ""){
                $sentencia .= "fecha between '".$request->get("fechaInicio")."' and '".$request->get("fechaFin")."'";
                if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                    $sentencia .= " and (id_cliente = ".$request->get("id_cliente");
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= " or folio = ".$request->get("folio");
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    $sentencia.=")";
                }
                else{
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= "and (folio = ".$request->get("folio");
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= " or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                        $sentencia.=")";
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "and (p.clearing_document like '%".$request->get("clearing")."%'";
                            $sentencia.=")";
                        }
                    }
                }
                
            }
            else{
                if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                    $sentencia .= "id_cliente = ".$request->get("id_cliente");
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= " or f.folio = ".$request->get("folio");
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                }
                else{
                    if($request->has("folio") && $request->get("folio") != ""){
                        $sentencia .= "f.folio = ".$request->get("folio");
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                    else{
                        if($request->has("clearing") && $request->get("clearing")){
                            $sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
                        }
                    }
                }
            }
        }
        else{
            if($request->has("id_cliente") && $request->get("id_cliente") != ""){
                $sentencia .= "id_cliente = ".$request->get("id_cliente");
                if($request->has("folio") && $request->get("folio") != ""){
                    $sentencia .= " or folio = ".$request->get("folio");
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
                else{
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
            }
            else{
                if($request->has("folio") && $request->get("folio") != ""){
                    $sentencia .= "folio = ".$request->get("folio");
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "or p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
                else{
                    if($request->has("clearing") && $request->get("clearing")){
                        $sentencia .= "p.clearing_document like '%".$request->get("clearing")."%'";
                    }
                }
            }
        }

        if($sentencia == ""){
            $datos = Facturas::all();
            $pdf = PDF::loadView('reporteImpuestosPDF', compact('datos'))->setPaper([0,0,720.00,1440.00], 'landscape');;

            return $pdf->download('Reporte_de_Impuestos.pdf');
        }
        else{
            $datos = DB::table("parcialidades as p")
            ->join("complemento as c", "p.clearing_document", "=", "c.clearing_document")
            ->whereRaw(DB::raw($sentencia))
            ->get();

            if($datos){
                $pdf = PDF::loadView('reporteImpuestosPDF', compact('datos'))->setPaper([0,0,720.00,1440.00], 'landscape');;

                return $pdf->download('Reporte_de_Impuestos.pdf');
            }
            else{
                return response()->json([
                    "respuesta" => 1
                ]);
            }
        }
    }
}
