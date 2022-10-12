<?php

namespace App\Http\Controllers;

use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
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

class ValidacionSAPController extends Controller
{
    public function index()
    {
    	$layouts = DB::table('bancos_l_SAP')
    	->get();

    	return view('SAT.validacionSAT',["layout" => $layouts]);

    }

    public function guardarPrueba(Request $request){
    	$elimina = DB::table("temporal_SAP")
    	->delete();

    	Session::put('layout', $request->get('layout'));
    	Excel::load($request->excel, function($reader){
    		$vieneCliente = false;
	    	$primero = 0;
	    	$ultimo = 0;

    		$lay = DB::table('bancos_l_SAP')
    		->where('id_ls', '=', Session::get('layout'))
    		->first();

    		$ID = $lay->ID;
		    $FOLIO = $lay->FOLIO;
		    $MONEDAPAGO = $lay->MONEDAPAGO;
		    $MONTOPAGO = $lay->MONTOPAGO;
		    $TIPOCAMBIOP = $lay->TIPOCAMBIOP;
		    $TIPODOC = $lay->TIPODOC;
		    $FECHAPAGO = $lay->FECHAPAGO;
		    $FOLIOS = $lay->FOLIOS;
		    $PARCIAL = $lay->PARCIAL;
		    $ASSIGNMENT = $lay->ASSIGNMENT;
		    $REFERENCE = $lay->REFERENCE;
		    $NUMREGIDTRIB = $lay->NUMREGIDTRIB;


		    $covestro = DB::table("covestro")
    		->first();
    		foreach ($reader->get() as $key => $row){
    			if($row[$ID] != ""){
    				$sap = new SAP_Pruebas_Model;
    				$sap->id_cliente = $row[$ID];
    				$id = $row[$ID];
    				$existe = DB::table("clientes")
	    			->where("id_cliente", '=', $id)
	    			->count();

	    			if($existe >= 1){
	    				$cliente = DB::table("clientes")
		    			->where("id_cliente", '=', $id)
		    			->first();

		    			if($cliente->residenciafiscal != "MX"){
			    			$sap->RFC_R = "XEXX010101000";
			    			$sap->NOMBRE_R = $cliente->nombre_c;
			    			$sap->DIRECCION_R = $cliente->direccion_c;
		    				$residencias = DB::table("residencia")
		    				->get();

		    				foreach($residencias as $resi) {
		    					if($resi->equivalencia == $cliente->residenciafiscal){
		    						$sap->RESIDENCIAFISCAL = $resi->resid;
		    					}
		    				}

		    				$sap->NUMREGIDTRIB = $row[$NUMREGIDTRIB];
		    			}

		    			else{
		    				$sap->RFC_R = $cliente->rfc_c;
			    			$sap->NOMBRE_R = $cliente->nombre_c;
			    			$sap->DIRECCION_R = $cliente->direccion_c;
			    			$sap->RESIDENCIAFISCAL = "";
			    			$sap->NUMREGIDTRIB = "";
		    			}
	    			}
	    			else{
	    				$sap->RFC_R = "El cliente con id ".$id." no existe";
	    				$sap->NOMBRE_R = "El cliente con id ".$id." no existe";
		    			$sap->DIRECCION_R = "El cliente con id ".$id." no existe";
		    			$sap->RESIDENCIAFISCAL = "El cliente con id ".$id." no existe";
		    			$sap->NUMREGIDTRIB = "El cliente con id ".$id." no existe";
	    			}
	    			$sap->REGIMEN = $covestro->regimen;
	    			$sap->RFC_E = $covestro->rfc_e;
	    			$sap->NOMBRE_E = $covestro->nombre_e;
	    			$sap->DIRECCION_E = $covestro->calle_e." ".$covestro->numext_e." ".$covestro->numint_e.", COLONIA ".$covestro->colonia_e.", CP. ".$covestro->cpostal_e;
	    			$sap->NUMPAGO = $covestro->numpago;

	    			$sap->LUGAREXPEDICION = $covestro->cpostal_e;
			        $sap->FOLIO = $row[$FOLIO];
			        $sap->MONEDAPAGO = $row[$MONEDAPAGO];
			        $sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
			        $sap->MONTOPAGOMXN = str_replace("-", "", $row["montomxn"]);
			        $sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
			        $sap->TIPODOC = $row[$TIPODOC];
			        $sap->FECHADOC = $row[$FECHAPAGO];
			        $sap->ASSIGNMENT = $row[$ASSIGNMENT];
			        $sap->REFERENCE = $row[$REFERENCE];
			        $sap->FOLIOS = substr($row[$FOLIOS], -7);
			        $sap->ID_DOC = substr($row[$FOLIOS], -7);
			        $sap->PARCIAL = $row[$PARCIAL];
					$sap->usuario = Session::get('user');
					$sap->save();
    			}

    			else{
    				$sap = new SAP_Pruebas_Model;
	    			$sap->RFC_R = "Este pago/factura no tiene id del cliente";
	    			$sap->NOMBRE_R = "Este pago/factura no tiene id del cliente";
		    		$sap->DIRECCION_R = "Este pago/factura no tiene id del cliente";
		    		$sap->RESIDENCIAFISCAL = "Este pago/factura no tiene id del cliente";
		    		$sap->NUMREGIDTRIB = "Este pago/factura no tiene id del cliente";
	    			$sap->REGIMEN = $covestro->regimen;
	    			$sap->RFC_E = $covestro->rfc_e;
	    			$sap->NOMBRE_E = $covestro->nombre_e;
	    			$sap->DIRECCION_E = $covestro->calle_e." ".$covestro->numext_e." ".$covestro->numint_e.", COLONIA ".$covestro->colonia_e.", CP. ".$covestro->cpostal_e;
	    			$sap->NUMPAGO = $covestro->numpago;
	    			$sap->LUGAREXPEDICION = $covestro->cpostal_e;
			        $sap->FOLIO = $row[$FOLIO];
			        $sap->MONEDAPAGO = $row[$MONEDAPAGO];
			        $sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
			        $sap->MONTOPAGOMXN = str_replace("-", "", $row["montomxn"]);
			        $sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
			        $sap->TIPODOC = $row[$TIPODOC];
			        $sap->FECHADOC = $row[$FECHAPAGO];
			        $sap->FOLIOS = substr($row[$FOLIOS], -7);
			        $sap->ID_DOC = substr($row[$FOLIOS], -7);
			        $sap->PARCIAL = $row[$PARCIAL];
					$sap->usuario = Session::get('user');
					$sap->save();
    			}
			}
    	});

    	$mostrar = DB::table('temporal_SAP')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	return response()->json($mostrar);
    }

    public function guardarDatos(Request $request)
    {
    	$clearing = "";
    	$fact = "";
    	$acum = 0;
    	$acum2 = 0;
    	$dz = false;
    	$prueba = DB::table('temporal_SAP')
    	->where('usuario', '=', Session::get('user'))
    	->get();

    	foreach($prueba as $p){
    		if($p->TIPODOC == "DZ"){
    			if($p->FOLIOS == 0 || $p->FOLIOS == "" || is_null($p->FOLIOS) || $p->FOLIOS == "0" || $p->FOLIOS == "#"){
    				$dz = true;
	    			$pago = new Pagos;
		    		$pago->clearing_document = $p->FOLIO;
					$pago->version = "1";
					$pago->fecha_clearing = "";
					$pago->regimen = $p->REGIMEN;
					$pago->lugarexpedicion = $p->LUGAREXPEDICION;
					$pago->residenciafiscal = $p->RESIDENCIAFISCAL;
					$pago->numregidtrib = $p->NUMREGIDTRIB;
					$pago->confirmacion = $p->CONFIRMACION;
					$pago->formap = "";
					$pago->monedaP = $p->MONEDAPAGO;
					$pago->fechap = "";
					$pago->fechadoc = $p->FECHADOC;
					$pago->assignment = $p->ASSIGNMENT;
					$pago->reference = $p->REFERENCE;
					$pago->tipocambioP = $p->TIPOCAMBIOP;	
					if($p->MONEDAPAGO != "MXN"){
						$monto = str_replace(" ", "", $p->MONTOPAGO);
					}
					else{
						$monto = str_replace(" ", "", $p->MONTOPAGOMXN);
					}
		            $monto = str_replace("$", "", $monto);
		            $monto = str_replace(",", "", $monto);
		            $monto = str_replace("MXN", "", $monto);
		            $monto = str_replace("mxn", "", $monto);
		            if($monto < 0){
						$pago->signo = "-";
					}
					else{
						$pago->signo = "+";
					}
					$monto = str_replace("-", "", $monto);
					if($p->MONEDAPAGO != "MXN"){
						$pago->montoP = (float)$monto - $acum;
					}
					else{
						$pago->montoP = (float)$monto - $acum2;
					}
					$pago->numeroperP = "";
					$pago->rfcctaord = "";
					$pago->bancoordext = "";
					$pago->ctaord = "";
					$pago->cataben = "";
					$pago->rfc_c = $p->RFC_R;
					$pago->nombre_c = $p->NOMBRE_R;
					$pago->rfc_e = $p->RFC_E;
					$pago->nombre_e = $p->NOMBRE_E;
					$pago->id_cliente = $p->id_cliente;
					$pago->timbrado = "0";
					$pago->save();

					$acum = 0;
					$acum2 = 0;
					$clearing = $p->FOLIO;
					$precio = "";
    			}
    			else{
    				$monto1 = str_replace(" ", "", $p->MONTOPAGO);
		            $monto1 = str_replace("$", "", $monto1);
		            $monto1 = str_replace(",", "", $monto1);
		            $monto1 = str_replace("MXN", "", $monto1);
		            $monto1 = str_replace("mxn", "", $monto1);
		            $monto1 = str_replace("-", "", $monto1);
		            $monto2 = str_replace(" ", "", $p->MONTOPAGOMXN);
		            $monto2 = str_replace("$", "", $monto2);
		            $monto2 = str_replace(",", "", $monto2);
		            $monto2 = str_replace("MXN", "", $monto2);
		            $monto2 = str_replace("mxn", "", $monto2);
		            $monto2 = str_replace("-", "", $monto2);
		            $acum = $acum + (float)$monto1;
		            $acum2 = $acum2 + (float)$monto2;
    			}
    		}
    		elseif($p->TIPODOC == "DC"){
    				$dz = true;
	    			$pago = new Pagos;
		    		$pago->clearing_document = $p->FOLIO;
					$pago->version = "1";
					$pago->fecha_clearing = "";
					$pago->regimen = $p->REGIMEN;
					$pago->lugarexpedicion = $p->LUGAREXPEDICION;
					$pago->residenciafiscal = $p->RESIDENCIAFISCAL;
					$pago->numregidtrib = $p->NUMREGIDTRIB;
					$pago->confirmacion = $p->CONFIRMACION;
					$pago->formap = "17";
					$pago->monedaP = $p->MONEDAPAGO;
					$pago->fechap = "$p->FECHADOC";
					$pago->fechadoc = $p->FECHADOC;
					$pago->assignment = $p->ASSIGNMENT;
					$pago->reference = $p->REFERENCE;
					$pago->tipocambioP = $p->TIPOCAMBIOP;	
					$monto = str_replace(" ", "", $p->MONTOPAGO);
		            $monto = str_replace("$", "", $monto);
		            $monto = str_replace(",", "", $monto);
		            $monto = str_replace("MXN", "", $monto);
		            $monto = str_replace("mxn", "", $monto);
		            if($monto < 0){
						$pago->signo = "-";
					}
					else{
						$pago->signo = "+";
					}
					$monto = str_replace("-", "", $monto);
					$pago->montoP = (float)$monto;
					$pago->numeroperP = "";
					$pago->rfcctaord = "";
					$pago->bancoordext = "";
					$pago->ctaord = "";
					$pago->cataben = "";
					$pago->rfc_c = $p->RFC_R;
					$pago->nombre_c = $p->NOMBRE_R;
					$pago->rfc_e = $p->RFC_E;
					$pago->nombre_e = $p->NOMBRE_E;
					$pago->timbrado = "0";
					$pago->save();
    		}
    		elseif($p->TIPODOC ==  "AB"){
    			$hay = DB::table("parcialidades")
    			->where("folio", "=", $p->FOLIOS)
    			->where("clearing_document", "=", $p->FOLIO)
    			->count();
    			if($hay < 1){
	    				
	    			$parti = explode(":", $p->PARCIAL);
	    			if(count($parti) == 3){
	    				$parcial = new Parcialidades;
	    				$parcial->tipcambio = $p->TIPOCAMBIOP;
	    				$parcial->moneda = $p->MONEDAPAGO;
	    				$numpar = str_split($parti[0]);
					    $parcial->numparcialidad = $numpar[1];
					    $isa = explode("$", $parti[1]);
					    $isa = str_replace(" ", "", $isa[1]);
			            $isa = str_replace("$", "", $isa);
			            $isa = str_replace(",", "", $isa);
			            $isa = str_replace("MXN", "", $isa);
			            $isa = str_replace("mxn", "", $isa);
			            $isa = str_replace("-", "", $isa);
					    $parcial->impsaldoant = $isa;
					    $ipa = explode("$", $parti[2]);
					    $ipa = str_replace(" ", "", $ipa[1]);
			            $ipa = str_replace("$", "", $ipa);
			            $ipa = str_replace(",", "", $ipa);
			            $ipa = str_replace("MXN", "", $ipa);
			            $ipa = str_replace("mxn", "", $ipa);
			            $ipa = str_replace("-", "", $ipa);
					    $parcial->imppagado = $ipa;
					    $parcial->impsaldoins = (float)$isa - (float)$ipa;
					    if($p->MONTOPAGO < 0){
							$parcial->signo = "-";
						}
						else{
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
					    $parcial->save();
					    $existe = DB::table('factura')
			    		->where('folio', '=', $p->FOLIOS)
			    		->count();
			    		if($existe < 1 && $p->FOLIOS != 0 && $p->FOLIOS != "0" && $p->FOLIOS != "" && !is_null($p->FOLIOS) && $p->FOLIOS != "0" && $p->FOLIOS != "#"){
			    			$factura = new Facturas;
				    		$precio = str_replace("$", "", $p->MONTOPAGO);
					    	$factura->folio = $p->FOLIOS;
					    	$factura->monto = $precio;
					    	$factura->moneda = $p->MONEDAPAGO;
					    	$factura->save();
			    		}
	    			}
	    			else{
	    				$numpar = str_split($p->PARCIAL);
	    				$precio = str_replace("$", "", $p->MONTOPAGO);
			    		$parcial = new Parcialidades;
	    				$parcial->tipcambio = $p->TIPOCAMBIOP;
	    				$parcial->moneda = $p->MONEDAPAGO;
	    				if(is_numeric($numpar[0])){
		    					$parcial->numparcialidad = $numpar[0];
		    				}
		    				else{
		    					$parcial->numparcialidad = "1";
		    				}
	    				$parcial->impsaldoant = $precio;
					    $parcial->imppagado = $precio;
					    $parcial->impsaldoins = "0";
					    if($p->MONTOPAGO < 0){
							$parcial->signo = "-";
						}
						else{
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
					    $parcial->save();

					    $existe = DB::table('factura')
			    		->where('folio', '=', $p->FOLIOS)
			    		->count();
			    		if($existe < 1 && $p->FOLIOS != 0 && $p->FOLIOS != "0" && $p->FOLIOS != "" && !is_null($p->FOLIOS) && $p->FOLIOS != "0" && $p->FOLIOS != "#"){
			    			$factura = new Facturas;
				    		$precio = str_replace("$", "", $p->MONTOPAGO);
					    	$factura->folio = $p->FOLIOS;
					    	$factura->monto = $precio;
					    	$factura->moneda = $p->MONEDAPAGO;
					    	$factura->save();
			    		}
	    			}
    			}
    		}
    		elseif($p->TIPODOC == "RV"){
    			$existe = DB::table('factura')
		    	->where('folio', '=', $p->FOLIOS)
		    	->count();
		    	if($existe < 1){
		    		$factura = new Facturas;
			    	$precio = str_replace("$", "", $p->MONTOPAGO);
				    $factura->folio = $p->FOLIOS;
				    $factura->monto = $precio;
				    $factura->moneda = $p->MONEDAPAGO;
				    $factura->save();
		    	}

		    	$existe = DB::table('parcialidades')
		    	->where('folio', '=', $p->FOLIOS)
		    	->where('clearing_document', '=', $p->FOLIO)
		    	->count();
		    	if($existe < 1){
		    		$precio = str_replace("$", "", $p->MONTOPAGO);
		    		$parcial = new Parcialidades;
    				$parcial->tipcambio = $p->TIPOCAMBIOP;
    				$parcial->moneda = $p->MONEDAPAGO;
    				$parcial->numparcialidad = "1";
    				$parcial->impsaldoant = $precio;
				    $parcial->imppagado = $precio;
				    $parcial->impsaldoins = "0";
				    if($p->MONTOPAGO < 0){
						$parcial->signo = "-";
					}
					else{
						$parcial->signo = "+";
					}
					$parcial->folio = $p->FOLIOS;
					$parcial->clearing_document = $p->FOLIO;
					$parcial->rfc_c = $p->RFC_R;
					$parcial->nombre_c = $p->NOMBRE_R;
				    $parcial->save();
		    	}
		    	elseif($existe%2 == 0){
		    		$precio = str_replace("$", "", $p->MONTOPAGO);
		    		$parcial = new Parcialidades;
    				$parcial->tipcambio = $p->TIPOCAMBIOP;
    				$parcial->moneda = $p->MONEDAPAGO;
    				$parcial->numparcialidad = "1";
    				$parcial->impsaldoant = $precio;
				    $parcial->imppagado = $precio;
				    $parcial->impsaldoins = "0";
				    if($p->MONTOPAGO < 0){
						$parcial->signo = "-";
					}
					else{
						$parcial->signo = "+";
					}
					$parcial->folio = $p->FOLIOS;
					$parcial->clearing_document = $p->FOLIO;
					$parcial->rfc_c = $p->RFC_R;
					$parcial->nombre_c = $p->NOMBRE_R;
				    $parcial->save();
		    	}
    		}
    		elseif($p->TIPODOC == "RW"){
    				if($p->MONEDAPAGO != "MXN"){
	    					$precio = str_replace("$", "", $p->MONTOPAGO);
	    				}
	    				else{
	    					$precio = str_replace("$", "", $p->MONTOPAGOMXN);
	    				}
    				$assig = substr($p->ASSIGNMENT, 13);
		    		$encuentra = DB::table('parcialidades')
		    		->where("imppagado", "=", $precio)
		    		->where("folio", "=", $assig)
		    		->where("clearing_document", "=", $p->FOLIO)
		    		->count();

		    		if($encuentra == 1){
		    			$pre = DB::table('parcialidades')
			    		->where("imppagado", "=", $precio)
			    		->where("folio", "=", $assig)
			    		->where("clearing_document", "=", $p->FOLIO)
			    		->first();

		    			$encuentra = DB::table('parcialidades')
			    		->where("id_par", "=", $pre->id_par)
			    		->delete();
		    		}

		    		else{
			    		$encuentra = DB::table('parcialidades')
			    		->where("folio", "=", $assig)
			    		->where("clearing_document", "=", $p->FOLIO)
			    		->count();

			    		if($encuentra == 1){
			    			$pre = DB::table('parcialidades')
				    		->where("folio", "=", $assig)
				    		->where("clearing_document", "=", $p->FOLIO)
				    		->first();

				    		DB::table('parcialidades')
				    		->where('id_par', '=', $pre->id_par)
				    		->update([
				    			"impsaldoant" => (float)$pre->impsaldoant - (float)$p->MONTOPAGO,
				    			"imppagado" => (float)$pre->imppagado - (float)$p->MONTOPAGO,
				    		]);
			    		}
			    		else{
			    			$encuentra = DB::table('parcialidades')
				    		->where("imppagado", "=", $precio)
				    		->where("clearing_document", "=", $p->FOLIO)
				    		->count();

				    		if($encuentra >= 1){
				    			$pre = DB::table('parcialidades')
					    		->where("imppagado", "=", $precio)
					    		->where("clearing_document", "=", $p->FOLIO)
				    			->orderBy('id_par', 'desc')
				    			->first();

				    			$encuentra = DB::table('parcialidades')
					    		->where("id_par", "=", $pre->id_par)
					    		->delete();
				    		}
				    		else{
				    			$encuentra = DB::table('parcialidades')
				    			->where("clearing_document", '=', $p->FOLIO)
				    			->max("imppagado");

				    			if($encuentra >= 1){
				    				$pre = DB::table('parcialidades')
						    		->where("clearing_document", "=", $p->FOLIO)
						    		->where("imppagado", "=", $encuentra)
						    		->first();

						    		DB::table('parcialidades')
						    		->where('id_par', '=', $pre->id_par)
						    		->update([
						    			"impsaldoant" => (float)$pre->impsaldoant - (float)$p->MONTOPAGO,
						    			"imppagado" => (float)$pre->imppagado - (float)$p->MONTOPAGO,
						    		]);
				    			}
				    		}
			    		}
		    		}
    		}
    	}

    	$mostrar = DB::table('temporal_SAP')
    	->where('usuario', '=', Session::get('user'))
    	->count();

    	if($mostrar > 0){
    		$mostrar2 = DB::table('temporal_SAP')
	    	->where('usuario', '=', Session::get('user'))
	    	->delete();

	    	return response()->json($mostrar);
    	}
    	else{
    		return response()->json([
                "respuesta" => "2"
            ]);
    	}
    }

    public function borrarPrueba(Request $request)
    {
    	$mostrar = DB::table('temporal_SAP')
    	->where('usuario', '=', Session::get('user'))
    	->delete();

    	$mostrar = DB::table('pago')
    	->delete();

    	$mostrar = DB::table('factura')
    	->delete();

    	$mostrar = DB::table('parcialidades')
    	->delete();

    	$mostrar = DB::table('incidencias')
    	->delete();

    	return response()->json([
    		"respuesta" => "si"
    	]);
    }
}
