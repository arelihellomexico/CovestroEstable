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

use Illuminate\Http\Request;

class ArchviosController extends Controller
{
    public function index(){
    	$covestro = DB::table("covestro")
    	->first();

    	$archivosSAP = DB::table('excel_SAP')
    	->where('integrado', '=', 0)
    	->get();

    	$archivosTeso = DB::table('excel_tesoreria')
    	->where('integrado', '=', 0)
    	->get();

    	$archivosCred = DB::table('excel_credito')
    	->where('integrado', '=', 0)
    	->get();

    	$historialSAP = DB::table('excel_SAP')
    	->where('integrado', '=', 1)
    	->get();

    	$historialTeso = DB::table('excel_tesoreria')
    	->where('integrado', '=', 1)
    	->get();

    	$historialCred = DB::table('excel_credito')
    	->where('integrado', '=', 1)
    	->get();

    	$proceso = 0;

    	if(Session::has('proceso')){
    		if(Session::get('proceso') != 0){
    			$proceso = DB::table('procesos')
    			->where('id_pro', '=', Session::get('proceso'))
    			->first();
    		}
    	}

    	$c = $proceso = DB::table('procesos')
    	->count();

    	$proceso = DB::table('procesos')
    	->max('id_pro');

    	$proceso = DB::table('procesos')
    	->where('id_pro', '=', $proceso)
    	->first();

    	return view('Administrador.integradorDeArchivos', ["covestro" => $covestro, "archivosSAP" => $archivosSAP, "archivosTeso" => $archivosTeso, "archivosCred" => $archivosCred, "historialSAP" =>$historialSAP, "historialTeso" => $historialTeso, "historialCred" => $historialCred, "proceso" => $proceso, 'c'=>$c]);
    }

    public function integrarComplemento(Request $request){
    	$busquedaSAP = "";
    	$busquedaTeso = "";
    	$busquedaCred = "";
    	$bs = "";
    	$bt = "";
    	$bc = "";

    	$monto = "";
    	$ex =  false;

    	$proceso = DB::table('procesos')
    	->max('id_pro');

    	$cuantosProcesos = DB::table('procesos')
    	->max('id_pro');

    	$proceso = DB::table('procesos')
    	->where('id_pro', '=', $proceso)//----cambiar la variable
    	->first();

    	if($cuantosProcesos != 0){//cual es el proposito para esta condicion
    		if($proceso->integracion == 1){
	    		DB::table("complemento")
	    		->where("id_pro", "=", $proceso->id_pro)
	    		->delete();

	    		DB::table("incidencias")
	    		->where("id_pro", "=", $proceso->id_pro)
	    		->delete();

	    		DB::table("incidencias_SAP")
	    		->truncate();

	    		DB::table("incidencias_tesoreria")
	    		->truncate();

	    		DB::table("procesos")
	    		->where("id_pro", "=", $proceso->id_pro)
	    		->delete();
	    	}
    	}

    	$archivosSAP = DB::table('excel_SAP')
    	->get();

    	$archivosTeso = DB::table('excel_tesoreria')
    	->get();

    	$archivosCred = DB::table('excel_credito')
    	->get();

    	$bancosSAP = DB::table("bancos_SAP")
		->get();

    	foreach ($archivosSAP as $sap) {//para todos los archivos que hay en la tabla excel_SAP
    		if($request->has('sap'.$sap->id_es)){//si existe un elemento o valor
    			$busquedaSAP .= "id_es = ".$sap->id_es." or ";
    			$bs .= $sap->id_es.",";
    		}
    	}
    	$busquedaSAP.="a";//se le asigna un texto como este:id_es=12 or a
    	foreach ($archivosTeso as $teso) {
    		if($request->has('teso'.$teso->id_et)){
    			$busquedaTeso .= "id_et = ".$teso->id_et." or ";
    			$bt .= $teso->id_et.",";
    		}
    	}
    	$busquedaTeso.="a";//se le asigna un texto como este:id_et=12 or a
    	foreach ($archivosCred as $cred) {
    		if($request->has('cred'.$cred->id_ec)){
    			$busquedaCred .= "id_ec = ".$cred->id_ec." or ";
    			$bc .= $cred->id_ec.",";
    		}
    	}
    	$busquedaCred.="a";

    	$busquedaSAP = str_replace("or a", "", $busquedaSAP);
		$busquedaTeso = str_replace("or a", "", $busquedaTeso);
		$busquedaCred = str_replace("or a", "", $busquedaCred);//se quita el or a y se sustituye por un caracter vacio

		$procesoNuevo = DB::table("procesos")->insertGetId([// se inserta un nuevo proceso en la tabla procesos
    		"nombre" => "Payment Complement ".date("Y-F-d"),
    		"fecha" => date("Y-m-d"),
    		"total" => 0,
    		"correctos" => 0,
    		"erroneos" => 0,
	    	"integracion" => 1,
	    	"timbrado" => 0,
	    	"obtencion" => 0,
	    	"id_es" => $bs,
	        "id_et" => $bt,
	        "id_ec" => $bc
	    ]);

	    Session::forget("proceso");
	    Session::put("proceso", $procesoNuevo);//asigna un a secion nueva con lo que devueleve al insertar elemento nuevo en la trabla

    	$res = "";
    	$mensaje = "";
    	$covestro = DB::table("covestro")
    	->first();//resive un solo elemento de la tabla covestro

    	$sap = DB::table("pago")
	    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
	    ->get();//se obtiene un arreglo de la tabla pago  donde id_es=algo

	    foreach ($sap as $sa) {//los pagos que se buscan con el id_es
	    	$ex = false;
	    	foreach ($bancosSAP as $b) {//todos las filas de la tabla bancos_SAP
		    		if($ex == false && $sa->clearing_document == $b->clearing_document){//si hay similitud en el clerin_folio de las columnas: clearing_document que se encuentran en las tablas bancos_SAP y pago
		    			$ex = true;
		    			if($sa->monedaP != "MXN"){// si la moneda es diferente de mx asigna el monto en dolares
		    				DB::table("pago")
		    				->where("clearing_document", "=", $sa->clearing_document)
		    				->update([
		    					"montoP" => $b->monto
		    				]);
		    			}
		    			else{// se asigna en monto en pesos mexicanos
		    				DB::table("pago")
		    				->where("clearing_document", "=", $sa->clearing_document)
		    				->update([
		    					"montoP" => $b->montomxn
		    				]);
		    			}
		    			break;
		    		}
		    	}
	    }

	    $sap = DB::table("pago")
	    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
	    ->get();
		
		if($covestro->usar_credito == 1){
			foreach ($sap as $s){
			    	if($s->reference == "Remisi??n" || $s->reference == "remisi??n" || $s->formap == "17" || $s->formap == 17){
			    				$fechaar = str_replace(".", "", $s->fechadoc);
							    			$fechaar = $fechaar."120000";
						    			$complemento = new Complemento;
							    		$complemento->id_pago = $s->id_pago;
									    $complemento->clearing_document = $s->clearing_document;
										$complemento->version = $s->version;
										$complemento->fecha_clearing = $s->fecha_clearing;
										$complemento->regimen = $s->regimen;
										$complemento->lugarexpedicion = $s->lugarexpedicion;
										$complemento->residenciafiscal = $s->residenciafiscal;
										$complemento->numregidtrib = $s->numregidtrib;
										$complemento->confirmacion = $s->confirmacion;
										if($s->reference == "Remisi??n" || $s->reference == "remisi??n"){
											$complemento->formap = "25";
										}
										else{
											$complemento->formap = $s->formap;
										}
										$complemento->monedaP = $s->monedaP;
										$complemento->fechap = $fechaar;
										$complemento->fechabus = $s->fechabus;
										$complemento->tipocambioP = $s->tipocambioP;
										$complemento->montoP = $s->montoP;
										$complemento->signo = $s->signo;
										$complemento->numeroperP = "";
										$complemento->rfcctaord = "";
										$complemento->bancoordext = "";
										$complemento->ctaord = "";
										$complemento->rfcctaben = "";
										$complemento->cataben = "";
										$complemento->rfc_c = $s->rfc_c;
										$complemento->nombre_c = $s->nombre_c;
										$complemento->rfc_e = $s->rfc_e;
										$complemento->nombre_e = $s->nombre_e;
										$complemento->id_cliente = $s->id_cliente;
										$complemento->timbrado = "1";
										$complemento->id_pro = $procesoNuevo;
										$complemento->id_es = $s->id_es;
										$complemento->save();

										DB::table("pago")
										->where('id_pago', '=', $s->id_pago)
										->update(["timbrado" => "1"]);
						    }
			    	elseif ($s->rfc_c != ""){
						//CREDITO NO MANEJA
						/*
			    		$documents = DB::table("credito")
			    		->where("clearing_document", "=", $s->clearing_document)
			    		->whereRaw(DB::raw('('.$busquedaCred.')'))
			    		->get();

			    		

			    		foreach ($documents as $doc) {
			    			if($doc->folio != 0 && $doc->folio != "0" && $doc->folio != "" && !is_null($doc->folio) && $doc->folio != "0" && $doc->folio != "#"){

			    			}
			    			else{
			    				$refer_documents = true;
			    			}
						}*/
						$refer_documents = false;
			    		if($refer_documents == false){
			    			$documents = DB::table("facturas_liquidadas")
				    		->where("clearing_document", "=", $s->clearing_document)
				    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
				    		->get();

				    		//$refer_documents = false; 9-03-2021

				    		foreach ($documents as $doc) {
								if($doc->folio != 0 && $doc->folio != "0" && $doc->folio != "" && !is_null($doc->folio) && $doc->folio != "0" && $doc->folio != "#"){// si no es nulo
									$refer_documents = false;
				    			}
				    			else{
				    				$refer_documents = true;
				    			}
				    		}
			    		}

			    		if($refer_documents == false){

				    		//Existe RFC o nombre de cliente en pago de SAP
				    		$tesoreria = DB::table("tesoreria")
							->whereRaw(DB::raw('('.$busquedaTeso.')'))
							->get();

				    		foreach ($tesoreria as $teso){
				    			if($teso->RFC_R != "" && !is_null($teso->RFC_R)){//Existe cliente en pago de tesoreria, ya sea RFC o nombre del cliente
				    				if($s->rfc_c == $teso->RFC_R){//Coincide el RFC, se va a comparar monto y moneda
					    				if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
					    					if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
					    						$simi = new Similaridades;
					    						$simi->id_tt = $teso->id_tt;
								    			$simi->RFC_R = $teso->RFC_R;
												$simi->MONTOP = $teso->MONTOP;
												$simi->MONEDAP = $teso->MONEDAP;
												$simi->NUMEROPERP = $teso->NUMEROPERP;
												$simi->RFCCTABEN = $teso->RFCCTABEN;
												$simi->CATABEN = $teso->CATABEN;
												$simi->FORMAP = $teso->FORMAP;
												$simi->RFCCTAORD = $teso->RFCCTAORD;
												$simi->BANCOORDEXT = $teso->BANCOORDEXT;
												$simi->CTAORD = $teso->CTAORD;
												$simi->FECHAPAG = $teso->FECHAPAG;
												$simi->save();
					    					}
					    				}
					    			}
					    			else{
					    				if($s->nombre_c == $teso->RFC_R){//Coincide Nombre, se va a comparar monto y moneda.
					    					if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
					    						if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
					    							$simi = new Similaridades;
					    							$simi->id_tt = $teso->id_tt;
									    			$simi->RFC_R = $teso->RFC_R;
													$simi->MONTOP = $teso->MONTOP;
													$simi->MONEDAP = $teso->MONEDAP;
													$simi->NUMEROPERP = $teso->NUMEROPERP;
													$simi->RFCCTABEN = $teso->RFCCTABEN;
													$simi->CATABEN = $teso->CATABEN;
													$simi->FORMAP = $teso->FORMAP;
													$simi->RFCCTAORD = $teso->RFCCTAORD;
													$simi->BANCOORDEXT = $teso->BANCOORDEXT;
													$simi->CTAORD = $teso->CTAORD;
													$simi->FECHAPAG = $teso->FECHAPAG;
													$simi->save();
						    					}
						    				}
					    				}
					    			}
				    			}
				    			else{//No existe en Tesoreria nombre o RFC, se procede a comparar monto y moneda.
				    				if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
					    				if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
					    					$simi = new Similaridades;
					    					$simi->id_tt = $teso->id_tt;
									    	$simi->RFC_R = $teso->RFC_R;
											$simi->MONTOP = $teso->MONTOP;
											$simi->MONEDAP = $teso->MONEDAP;
											$simi->NUMEROPERP = $teso->NUMEROPERP;
											$simi->RFCCTABEN = $teso->RFCCTABEN;
											$simi->CATABEN = $teso->CATABEN;
											$simi->FORMAP = $teso->FORMAP;
											$simi->RFCCTAORD = $teso->RFCCTAORD;
											$simi->BANCOORDEXT = $teso->BANCOORDEXT;
											$simi->CTAORD = $teso->CTAORD;
											$simi->FECHAPAG = $teso->FECHAPAG;
											$simi->save();
						    			}
						    			else{
							    			$res = "Las monedas no coinciden";
							    		}
						    		}
						    		else{
						    			$res = "Los montos no coinciden";
						    		}
				    			}
				    		}

				    		$conteo = DB::table("similaridades")
						    ->count();

						    $simil = DB::table("similaridades")
						    ->get();

						    if($conteo == 0){
						    	$incidencia = new Incidencias;
						    	$incidencia->id_pago = $s->id_pago;
							    $incidencia->clearing_document = $s->clearing_document;
								$incidencia->version = $s->version;
								$incidencia->fecha_clearing = $s->fecha_clearing;
								$incidencia->regimen = $s->regimen;
								$incidencia->lugarexpedicion = $s->lugarexpedicion;
								$incidencia->residenciafiscal = $s->residenciafiscal;
								$incidencia->numregidtrib = $s->numregidtrib;
								$incidencia->confirmacion = $s->confirmacion;
								$incidencia->formap = $s->formap;
								$incidencia->monedaP = $s->monedaP;
								$incidencia->fechap = $s->fechap;
								$incidencia->tipocambioP = $s->tipocambioP;
								$incidencia->montoP = $s->montoP;
								$incidencia->signo = $s->signo;
								$incidencia->numeroperP = $s->numeroperP;
								$incidencia->rfcctaord = $s->rfcctaord;
								$incidencia->bancoordext = $s->bancoordext;
								$incidencia->ctaord = $s->ctaord;
								$incidencia->rfcctaben = $s->rfcctaben;
								$incidencia->cataben = $s->cataben;
								$incidencia->rfc_c = $s->rfc_c;
								$incidencia->nombre_c = $s->nombre_c;
								$incidencia->rfc_e = $s->rfc_e;
								$incidencia->nombre_e = $s->nombre_e;
								$incidencia->id_cliente = $s->id_cliente;
								$incidencia->timbrado = "No existe relaci??n de montos y monedas entre tesoreria y SAP";
								$incidencia->id_pro = $procesoNuevo;
								$incidencia->id_es = $s->id_es;
								$incidencia->save();

								DB::table("pago")
								->where('clearing_document', '=', $s->clearing_document)
								->whereRaw(DB::raw('('.$busquedaSAP.')'))
								->update(["timbrado" => "No existe relaci??n de montos y monedas entre tesoreria y SAP"]);
						    }
						    elseif($conteo == 1){ // Revisa si hay una similaridad guardada
						    	$cfdirels = DB::table("credito")
						    	->where('clearing_document', '=', $s->clearing_document)
						    	->whereRaw(DB::raw('('.$busquedaCred.')'))
						    	->count();

						    	if($cfdirels > 0){
						    		$cfdirels = DB::table("credito")
						    		->where('clearing_document', '=', $s->clearing_document)
						    		->whereRaw(DB::raw('('.$busquedaCred.')'))
						    		->get();

						    		$repetidos = false;

						    		foreach ($cfdirels as $cfdi) {
						    			$solo_uno = DB::table('credito')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaCred.')'))
							    			->count();

							    			if($solo_uno > 1){
							    				$solo_uno = DB::table('credito')
							    				->where('folio', '=', $cfdi->folio)
							    				->where('imppagado', '=', $cfdi->imppagado)
							    				->where('clearing_document', '=', $cfdi->clearing_document)
							    				->whereRaw(DB::raw('('.$busquedaCred.')'))
							    				->delete();

							    				$solo_uno = DB::table('credito')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaCred.')'))
								    			->count();

							    				if($solo_uno > 1){
							    					$repetidos = true;
							    				}
							    			}
						    		}

						    		if($repetidos == false){
						    			$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $cfdi->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $cfdi->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $cfdi->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $cfdi->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.","; //Obtiene folio de factura de tabla credito
									    		}
								    		}
						    			}
						    			if($saldadoCredito == false && $saldadoLiquidadas == false){
						    				$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('id_pago', '=', $s->id_pago)
											->update(["timbrado" => "1"]);

											DB::table("tesoreria")
											->where('id_tt', '=', $simil[0]->id_tt)
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
						    			}
						    			else{
						    				$incidencia = new Incidencias;
									    	$incidencia->id_pago = $s->id_pago;
										    $incidencia->clearing_document = $s->clearing_document;
											$incidencia->version = $s->version;
											$incidencia->fecha_clearing = $s->fecha_clearing;
											$incidencia->regimen = $s->regimen;
											$incidencia->lugarexpedicion = $s->lugarexpedicion;
											$incidencia->residenciafiscal = $s->residenciafiscal;
											$incidencia->numregidtrib = $s->numregidtrib;
											$incidencia->confirmacion = $s->confirmacion;
											$incidencia->formap = $s->formap;
											$incidencia->monedaP = $s->monedaP;
											$incidencia->fechap = $s->fechap;
											$incidencia->tipocambioP = $s->tipocambioP;
											$incidencia->montoP = $s->montoP;
											$incidencia->signo = $s->signo;
											$incidencia->numeroperP = $s->numeroperP;
											$incidencia->rfcctaord = $s->rfcctaord;
											$incidencia->bancoordext = $s->bancoordext;
											$incidencia->ctaord = $s->ctaord;
											$incidencia->rfcctaben = $s->rfcctaben;
											$incidencia->cataben = $s->cataben;
											$incidencia->rfc_c = $s->rfc_c;
											$incidencia->nombre_c = $s->nombre_c;
											$incidencia->rfc_e = $s->rfc_e;
											$incidencia->nombre_e = $s->nombre_e;
											$incidencia->id_cliente = $s->id_cliente;
											$incidencia->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu;
											$incidencia->id_pro = $procesoNuevo;
											$incidencia->id_es = $s->id_es;
											$incidencia->save();

											DB::table("pago")
											->where('clearing_document', '=', $s->clearing_document)
											->whereRaw(DB::raw('('.$busquedaSAP.')'))
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);
						    			}
						    		}
						    		else{
						    			$incidencia = new Incidencias;
							    		$incidencia->id_pago = $s->id_pago;
									    $incidencia->clearing_document = $s->clearing_document;
										$incidencia->version = $s->version;
										$incidencia->fecha_clearing = $s->fecha_clearing;
										$incidencia->regimen = $s->regimen;
										$incidencia->lugarexpedicion = $s->lugarexpedicion;
										$incidencia->residenciafiscal = $s->residenciafiscal;
										$incidencia->numregidtrib = $s->numregidtrib;
										$incidencia->confirmacion = $s->confirmacion;
										$incidencia->formap = $s->formap;
										$incidencia->monedaP = $s->monedaP;
										$incidencia->fechap = $s->fechap;
										$incidencia->tipocambioP = $s->tipocambioP;
										$incidencia->montoP = $s->montoP;
										$incidencia->signo = $s->signo;
										$incidencia->numeroperP = $s->numeroperP;
										$incidencia->rfcctaord = $s->rfcctaord;
										$incidencia->bancoordext = $s->bancoordext;
										$incidencia->ctaord = $s->ctaord;
										$incidencia->rfcctaben = $s->rfcctaben;
										$incidencia->cataben = $s->cataben;
										$incidencia->rfc_c = $s->rfc_c;
										$incidencia->nombre_c = $s->nombre_c;
										$incidencia->rfc_e = $s->rfc_e;
										$incidencia->nombre_e = $s->nombre_e;
										$incidencia->id_cliente = $s->id_cliente;
										$incidencia->timbrado = "Se ha registrado un folio mas de una vez, en este pago.";
										$incidencia->id_pro = $procesoNuevo;
										$incidencia->id_es = $s->id_es;
										$incidencia->save();

										DB::table("pago")
										->where('id_pago', '=', $s->id_pago)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("similaridades")
										->delete();
						    		}
						    	}

						    	else{
						    		$liquidadas = DB::table("facturas_liquidadas")
						    		->where('clearing_document', '=', $s->clearing_document)
						    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    		->count();

						    		if($liquidadas > 0){
						    			$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $cfdi->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $cfdi->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $cfdi->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $cfdi->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			if($saldadoCredito == false && $saldadoLiquidadas == false){
						    				$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('id_pago', '=', $s->id_pago)
											->update(["timbrado" => "1"]);

											DB::table("tesoreria")
											->where('id_tt', '=', $simil[0]->id_tt)
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
						    			}
						    			else{
						    				$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu;
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('clearing_document', '=', $s->clearing_document)
											->whereRaw(DB::raw('('.$busquedaSAP.')'))
											->update(["timbrado" => "1"]);
						    			}
						    		}
						    		else{
							    		$incidencia = new Incidencias;
							    		$incidencia->id_pago = $s->id_pago;
									    $incidencia->clearing_document = $s->clearing_document;
										$incidencia->version = $s->version;
										$incidencia->fecha_clearing = $s->fecha_clearing;
										$incidencia->regimen = $s->regimen;
										$incidencia->lugarexpedicion = $s->lugarexpedicion;
										$incidencia->residenciafiscal = $s->residenciafiscal;
										$incidencia->numregidtrib = $s->numregidtrib;
										$incidencia->confirmacion = $s->confirmacion;
										$incidencia->formap = $s->formap;
										$incidencia->monedaP = $s->monedaP;
										$incidencia->fechap = $s->fechap;
										$incidencia->tipocambioP = $s->tipocambioP;
										$incidencia->montoP = $s->montoP;
										$incidencia->signo = $s->signo;
										$incidencia->numeroperP = $s->numeroperP;
										$incidencia->rfcctaord = $s->rfcctaord;
										$incidencia->bancoordext = $s->bancoordext;
										$incidencia->ctaord = $s->ctaord;
										$incidencia->rfcctaben = $s->rfcctaben;
										$incidencia->cataben = $s->cataben;
										$incidencia->rfc_c = $s->rfc_c;
										$incidencia->nombre_c = $s->nombre_c;
										$incidencia->rfc_e = $s->rfc_e;
										$incidencia->nombre_e = $s->nombre_e;
										$incidencia->id_cliente = $s->id_cliente;
										$incidencia->timbrado = "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1";
										$incidencia->id_pro = $procesoNuevo;
										$incidencia->id_es = $s->id_es;
										$incidencia->save();

										DB::table("pago")
										->where('id_pago', '=', $s->id_pago)
										->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1"]);

										DB::table("similaridades")
										->delete();
							    	}
						    	}
						    }
						    else{
						    	$similares = DB::table("similaridades")
						    	->get();

						    	$sol = DB::table("similaridades")->delete();

						    	foreach ($similares as $si) {
						    		if(strlen($si->FECHAPAG) == 23){
							    		$separacion = explode(" ", $si->FECHAPAG);
								    	$date = explode("-", $separacion[0]);
								    	$date = $date[2]."".$date[1]."".$date[0];
								    	$fecha_T = $date;
							    	}
							    	else{
							    		$fecha_T =  $si->FECHAPAG;
							    	}
							    	$fecha_S = str_replace(".", "", $s->fechadoc);

						    		if($fecha_S == $fecha_T){//Coincide la moneda. Se va a similaridades.
					    				$simi = new Similaridades;
					    				$simi->id_tt = $si->id_tt;
									    $simi->RFC_R = $si->RFC_R;
										$simi->MONTOP = $si->MONTOP;
										$simi->MONEDAP = $si->MONEDAP;
										$simi->NUMEROPERP = $si->NUMEROPERP;
										$simi->RFCCTABEN = $si->RFCCTABEN;
										$simi->CATABEN = $si->CATABEN;
										$simi->FORMAP = $si->FORMAP;
										$simi->RFCCTAORD = $si->RFCCTAORD;
										$simi->BANCOORDEXT = $si->BANCOORDEXT;
										$simi->CTAORD = $si->CTAORD;
										$simi->FECHAPAG = $si->FECHAPAG;
										$simi->save();
						    		}
						    	}

						    	$conteo = DB::table("similaridades")
							    ->count();

							    $simil = DB::table("similaridades")
							    ->get();

							    if($conteo == 0){
							    	$incidencia = new Incidencias;
							    	$incidencia->id_pago = $s->id_pago;
								    $incidencia->clearing_document = $s->clearing_document;
									$incidencia->version = $s->version;
									$incidencia->fecha_clearing = $s->fecha_clearing;
									$incidencia->regimen = $s->regimen;
									$incidencia->lugarexpedicion = $s->lugarexpedicion;
									$incidencia->residenciafiscal = $s->residenciafiscal;
									$incidencia->numregidtrib = $s->numregidtrib;
									$incidencia->confirmacion = $s->confirmacion;
									$incidencia->formap = $s->formap;
									$incidencia->monedaP = $s->monedaP;
									$incidencia->fechap = $s->fechap;
									$incidencia->tipocambioP = $s->tipocambioP;
									$incidencia->montoP = $s->montoP;
									$incidencia->signo = $s->signo;
									$incidencia->numeroperP = $s->numeroperP;
									$incidencia->rfcctaord = $s->rfcctaord;
									$incidencia->bancoordext = $s->bancoordext;
									$incidencia->ctaord = $s->ctaord;
									$incidencia->rfcctaben = $s->rfcctaben;
									$incidencia->cataben = $s->cataben;
									$incidencia->rfc_c = $s->rfc_c;
									$incidencia->nombre_c = $s->nombre_c;
									$incidencia->rfc_e = $s->rfc_e;
									$incidencia->nombre_e = $s->nombre_e;
									$incidencia->id_cliente = $s->id_cliente;
									$incidencia->timbrado = "No existe relaci??n de fechas de pago entre tesoreria y SAP";
									$incidencia->id_pro = $procesoNuevo;
									$incidencia->id_es = $s->id_es;
									$incidencia->save();

									DB::table("pago")
									->where('clearing_document', '=', $s->clearing_document)
									->whereRaw(DB::raw('('.$busquedaSAP.')'))
									->update(["timbrado" => "No existe relaci??n de fechas de pago entre tesoreria y SAP"]);
							    }
							    elseif($conteo == 1){
								    	$cfdirels = DB::table("credito")
								    	->where('clearing_document', '=', $s->clearing_document)
								    	->whereRaw(DB::raw('('.$busquedaCred.')'))
								    	->count();

								    	if($cfdirels > 0){
								    		$cfdirels = DB::table("credito")
								    		->where('clearing_document', '=', $s->clearing_document)
								    		->whereRaw(DB::raw('('.$busquedaCred.')'))
								    		->get();

								    		$repetidos = false;

								    		foreach ($cfdirels as $cfdi) {
								    			$solo_uno = DB::table('credito')
									    			->where('folio', '=', $cfdi->folio)
									    			->where('clearing_document', '=', $cfdi->clearing_document)
									    			->whereRaw(DB::raw('('.$busquedaCred.')'))
									    			->count();

									    			if($solo_uno > 1){
									    				$solo_uno = DB::table('credito')
									    				->where('folio', '=', $cfdi->folio)
									    				->where('imppagado', '=', $cfdi->imppagado)
									    				->where('clearing_document', '=', $cfdi->clearing_document)
									    				->whereRaw(DB::raw('('.$busquedaCred.')'))
									    				->delete();

									    				$solo_uno = DB::table('credito')
										    			->where('folio', '=', $cfdi->folio)
										    			->where('clearing_document', '=', $cfdi->clearing_document)
										    			->whereRaw(DB::raw('('.$busquedaCred.')'))
										    			->count();

									    				if($solo_uno > 1){
									    					$repetidos = true;
									    				}
									    			}
								    		}

								    		if($repetidos == false){
								    			$saldadoCredito = false;
								    			$numSalCred = "";
								    			$saldadoLiquidadas = false;
								    			$numSalLiqu = "";
								    			foreach ($cfdirels as $cfdi) {
								    				$ultimosC = DB::table("credito")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->count();
										    		if($ultimosC > 0){
										    			$ultimoCredito = DB::table("credito")
											    		->where('clearing_document', '<', $s->clearing_document)
											    		->where('folio', '=', $cfdi->folio)
											    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
											    		->orderBy("id_cre", "desc")
											    		->first();
											    		if($ultimoCredito->impsaldoins == 0){
											    			$saldadoCredito = true;
											    			$numSalCred .= $cfdi->folio.",";
											    		}
										    		}
								    			}
								    			foreach ($cfdirels as $cfdi) {
								    				$ultimosL = DB::table("facturas_liquidadas")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->count();
										    		if($ultimosL > 0){
										    			$ultimoLiquidada = DB::table("facturas_liquidadas")
											    		->where('clearing_document', '<', $s->clearing_document)
											    		->where('folio', '=', $cfdi->folio)
											    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
											    		->orderBy("id_cre", "desc")
											    		->first();
											    		if($ultimoLiquidada->impsaldoins == 0){
											    			$saldadoLiquidadas = true;
											    			$numSalLiqu .= $cfdi->folio.",";
											    		}
										    		}
								    			}
								    			if($saldadoCredito == false && $saldadoLiquidadas == false){
								    				$complemento = new Complemento;
										    		$complemento->id_pago = $s->id_pago;
												    $complemento->clearing_document = $s->clearing_document;
													$complemento->version = $s->version;
													$complemento->fecha_clearing = $s->fecha_clearing;
													$complemento->regimen = $s->regimen;
													$complemento->lugarexpedicion = $s->lugarexpedicion;
													$complemento->residenciafiscal = $s->residenciafiscal;
													$complemento->numregidtrib = $s->numregidtrib;
													$complemento->confirmacion = $s->confirmacion;
													$complemento->formap = $simil[0]->FORMAP;
													$complemento->monedaP = $s->monedaP;
													$complemento->fechap = $simil[0]->FECHAPAG;
													$complemento->fechabus = $s->fechabus;
													$complemento->tipocambioP = $s->tipocambioP;
													$complemento->montoP = $s->montoP;
													$complemento->signo = $s->signo;
													$complemento->numeroperP = $simil[0]->NUMEROPERP;
													$complemento->rfcctaord = $simil[0]->RFCCTAORD;
													$complemento->bancoordext = $simil[0]->BANCOORDEXT;
													$complemento->ctaord = $simil[0]->CTAORD;
													$complemento->rfcctaben = $simil[0]->RFCCTABEN;
													if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
														if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
															$complemento->cataben = "014180825008497793";
														}
														elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
															$complemento->cataben = "014180655050951787";
														}
														else{
															$complemento->cataben = $simil[0]->CATABEN;
														}
													}
													else{
														$complemento->cataben = $simil[0]->CATABEN;
													}
													$complemento->rfc_c = $s->rfc_c;
													$complemento->nombre_c = $s->nombre_c;
													$complemento->rfc_e = $s->rfc_e;
													$complemento->nombre_e = $s->nombre_e;
													$complemento->id_cliente = $s->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("pago")
													->where('id_pago', '=', $s->id_pago)
													->update(["timbrado" => "1"]);

													DB::table("tesoreria")
													->where('id_tt', '=', $simil[0]->id_tt)
													->update(["timbrado" => "1"]);

													DB::table("similaridades")
													->delete();
								    			}
								    			else{
								    				$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu;
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

													DB::table("pago")
													->where('clearing_document', '=', $s->clearing_document)
													->whereRaw(DB::raw('('.$busquedaSAP.')'))
													->update(["timbrado" => "1"]);
								    			}
								    		}
								    		else{
								    			$incidencia = new Incidencias;
									    		$incidencia->id_pago = $s->id_pago;
											    $incidencia->clearing_document = $s->clearing_document;
												$incidencia->version = $s->version;
												$incidencia->fecha_clearing = $s->fecha_clearing;
												$incidencia->regimen = $s->regimen;
												$incidencia->lugarexpedicion = $s->lugarexpedicion;
												$incidencia->residenciafiscal = $s->residenciafiscal;
												$incidencia->numregidtrib = $s->numregidtrib;
												$incidencia->confirmacion = $s->confirmacion;
												$incidencia->formap = $s->formap;
												$incidencia->monedaP = $s->monedaP;
												$incidencia->fechap = $s->fechap;
												$incidencia->tipocambioP = $s->tipocambioP;
												$incidencia->montoP = $s->montoP;
												$incidencia->signo = $s->signo;
												$incidencia->numeroperP = $s->numeroperP;
												$incidencia->rfcctaord = $s->rfcctaord;
												$incidencia->bancoordext = $s->bancoordext;
												$incidencia->ctaord = $s->ctaord;
												$incidencia->rfcctaben = $s->rfcctaben;
												$incidencia->cataben = $s->cataben;
												$incidencia->rfc_c = $s->rfc_c;
												$incidencia->nombre_c = $s->nombre_c;
												$incidencia->rfc_e = $s->rfc_e;
												$incidencia->nombre_e = $s->nombre_e;
												$incidencia->id_cliente = $s->id_cliente;
												$incidencia->timbrado = "Se ha registrado un folio mas de una vez, en este pago.";
												$incidencia->id_pro = $procesoNuevo;
												$incidencia->id_es = $s->id_es;
												$incidencia->save();

												DB::table("pago")
												->where('id_pago', '=', $s->id_pago)
												->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

												DB::table("similaridades")
												->delete();
								    		}
								    	}

								    	else{
								    		$liquidadas = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '=', $s->clearing_document)
								    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();

								    		if($liquidadas > 0){
								    			$saldadoCredito = false;
								    			$numSalCred = "";
								    			$saldadoLiquidadas = false;
								    			$numSalLiqu = "";
								    			foreach ($liquidadas as $cfdi) {
								    				$ultimosC = DB::table("credito")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->count();
										    		if($ultimosC > 0){
										    			$ultimoCredito = DB::table("credito")
											    		->where('clearing_document', '<', $s->clearing_document)
											    		->where('folio', '=', $cfdi->folio)
											    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
											    		->orderBy("id_cre", "desc")
											    		->first();
											    		if($ultimoCredito->impsaldoins == 0){
											    			$saldadoCredito = true;
											    			$numSalCred .= $cfdi->folio.",";
											    		}
										    		}
								    			}
								    			foreach ($liquidadas as $cfdi) {
								    				$ultimosL = DB::table("facturas_liquidadas")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->count();
										    		if($ultimosL > 0){
										    			$ultimoLiquidada = DB::table("facturas_liquidadas")
											    		->where('clearing_document', '<', $s->clearing_document)
											    		->where('folio', '=', $cfdi->folio)
											    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
											    		->orderBy("id_cre", "desc")
											    		->first();
											    		if($ultimoLiquidada->impsaldoins == 0){
											    			$saldadoLiquidadas = true;
											    			$numSalLiqu .= $cfdi->folio.",";
											    		}
										    		}
								    			}
								    			if($saldadoCredito == false && $saldadoLiquidadas == false){
								    				$complemento = new Complemento;
										    		$complemento->id_pago = $s->id_pago;
												    $complemento->clearing_document = $s->clearing_document;
													$complemento->version = $s->version;
													$complemento->fecha_clearing = $s->fecha_clearing;
													$complemento->regimen = $s->regimen;
													$complemento->lugarexpedicion = $s->lugarexpedicion;
													$complemento->residenciafiscal = $s->residenciafiscal;
													$complemento->numregidtrib = $s->numregidtrib;
													$complemento->confirmacion = $s->confirmacion;
													$complemento->formap = $simil[0]->FORMAP;
													$complemento->monedaP = $s->monedaP;
													$complemento->fechap = $simil[0]->FECHAPAG;
													$complemento->fechabus = $s->fechabus;
													$complemento->tipocambioP = $s->tipocambioP;
													$complemento->montoP = $s->montoP;
													$complemento->signo = $s->signo;
													$complemento->numeroperP = $simil[0]->NUMEROPERP;
													$complemento->rfcctaord = $simil[0]->RFCCTAORD;
													$complemento->bancoordext = $simil[0]->BANCOORDEXT;
													$complemento->ctaord = $simil[0]->CTAORD;
													$complemento->rfcctaben = $simil[0]->RFCCTABEN;
													if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
														if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
															$complemento->cataben = "014180825008497793";
														}
														elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
															$complemento->cataben = "014180655050951787";
														}
														else{
															$complemento->cataben = $simil[0]->CATABEN;
														}
													}
													else{
														$complemento->cataben = $simil[0]->CATABEN;
													}
													$complemento->rfc_c = $s->rfc_c;
													$complemento->nombre_c = $s->nombre_c;
													$complemento->rfc_e = $s->rfc_e;
													$complemento->nombre_e = $s->nombre_e;
													$complemento->id_cliente = $s->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("pago")
													->where('id_pago', '=', $s->id_pago)
													->update(["timbrado" => "1"]);

													DB::table("tesoreria")
													->where('id_tt', '=', $simil[0]->id_tt)
													->update(["timbrado" => "1"]);

													DB::table("similaridades")
													->delete();
								    			}
								    			else{
								    				$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu;
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

													DB::table("pago")
													->where('clearing_document', '=', $s->clearing_document)
													->whereRaw(DB::raw('('.$busquedaSAP.')'))
													->update(["timbrado" => "1"]);
								    			}
								    		}
								    		else{
									    		$incidencia = new Incidencias;
									    		$incidencia->id_pago = $s->id_pago;
											    $incidencia->clearing_document = $s->clearing_document;
												$incidencia->version = $s->version;
												$incidencia->fecha_clearing = $s->fecha_clearing;
												$incidencia->regimen = $s->regimen;
												$incidencia->lugarexpedicion = $s->lugarexpedicion;
												$incidencia->residenciafiscal = $s->residenciafiscal;
												$incidencia->numregidtrib = $s->numregidtrib;
												$incidencia->confirmacion = $s->confirmacion;
												$incidencia->formap = $s->formap;
												$incidencia->monedaP = $s->monedaP;
												$incidencia->fechap = $s->fechap;
												$incidencia->tipocambioP = $s->tipocambioP;
												$incidencia->montoP = $s->montoP;
												$incidencia->signo = $s->signo;
												$incidencia->numeroperP = $s->numeroperP;
												$incidencia->rfcctaord = $s->rfcctaord;
												$incidencia->bancoordext = $s->bancoordext;
												$incidencia->ctaord = $s->ctaord;
												$incidencia->rfcctaben = $s->rfcctaben;
												$incidencia->cataben = $s->cataben;
												$incidencia->rfc_c = $s->rfc_c;
												$incidencia->nombre_c = $s->nombre_c;
												$incidencia->rfc_e = $s->rfc_e;
												$incidencia->nombre_e = $s->nombre_e;
												$incidencia->id_cliente = $s->id_cliente;
												$incidencia->timbrado = "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1";
												$incidencia->id_pro = $procesoNuevo;
												$incidencia->id_es = $s->id_es;
												$incidencia->save();

												DB::table("pago")
												->where('id_pago', '=', $s->id_pago)
												->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1"]);

												DB::table("similaridades")
												->delete();
									    	}
								    	}
								    }
							    else{
							    	$incidencia = new Incidencias;
							    	$incidencia->id_pago = $s->id_pago;
								    $incidencia->clearing_document = $s->clearing_document;
									$incidencia->version = $s->version;
									$incidencia->fecha_clearing = $s->fecha_clearing;
									$incidencia->regimen = $s->regimen;
									$incidencia->lugarexpedicion = $s->lugarexpedicion;
									$incidencia->residenciafiscal = $s->residenciafiscal;
									$incidencia->numregidtrib = $s->numregidtrib;
									$incidencia->confirmacion = $s->confirmacion;
									$incidencia->formap = $s->formap;
									$incidencia->monedaP = $s->monedaP;
									$incidencia->fechap = $s->fechap;
									$incidencia->tipocambioP = $s->tipocambioP;
									$incidencia->montoP = $s->montoP;
									$incidencia->signo = $s->signo;
									$incidencia->numeroperP = $s->numeroperP;
									$incidencia->rfcctaord = $s->rfcctaord;
									$incidencia->bancoordext = $s->bancoordext;
									$incidencia->ctaord = $s->ctaord;
									$incidencia->rfcctaben = $s->rfcctaben;
									$incidencia->cataben = $s->cataben;
									$incidencia->rfc_c = $s->rfc_c;
									$incidencia->nombre_c = $s->nombre_c;
									$incidencia->rfc_e = $s->rfc_e;
									$incidencia->nombre_e = $s->nombre_e;
									$incidencia->id_cliente = $s->id_cliente;
									$incidencia->timbrado = "Hay ambig??edad entre tesorer??a y SAP. Multiples datos de tesorer??a se asocian con este pago de SAP.";
									$incidencia->id_pro = $procesoNuevo;
									$incidencia->id_es = $s->id_es;
									$incidencia->save();

									DB::table("pago")
									->where('id_pago', '=', $s->id_pago)
									->update(["timbrado" => "Hay ambig??edad entre tesorer??a y SAP. Multiples datos de tesorer??a se asocian con este pago de SAP."]);

									DB::table("similaridades")
										->truncate();
							    }
						    }
			    		}
			    		else{
			    			$incidencia = new Incidencias;
					    	$incidencia->id_pago = $s->id_pago;
							$incidencia->clearing_document = $s->clearing_document;
							$incidencia->version = $s->version;
							$incidencia->fecha_clearing = $s->fecha_clearing;
							$incidencia->regimen = $s->regimen;
							$incidencia->lugarexpedicion = $s->lugarexpedicion;
							$incidencia->residenciafiscal = $s->residenciafiscal;
							$incidencia->numregidtrib = $s->numregidtrib;
							$incidencia->confirmacion = $s->confirmacion;
							$incidencia->formap = $s->formap;
							$incidencia->monedaP = $s->monedaP;
							$incidencia->fechap = $s->fechap;
							$incidencia->tipocambioP = $s->tipocambioP;
							$incidencia->montoP = $s->montoP;
							$incidencia->signo = $s->signo;
							$incidencia->numeroperP = $s->numeroperP;
							$incidencia->rfcctaord = $s->rfcctaord;
							$incidencia->bancoordext = $s->bancoordext;
							$incidencia->ctaord = $s->ctaord;
							$incidencia->rfcctaben = $s->rfcctaben;
							$incidencia->cataben = $s->cataben;
							$incidencia->rfc_c = $s->rfc_c;
							$incidencia->nombre_c = $s->nombre_c;
							$incidencia->rfc_e = $s->rfc_e;
							$incidencia->nombre_e = $s->nombre_e;
							$incidencia->id_cliente = $s->id_cliente;
							$incidencia->timbrado = "Los documentos referenciados de este pago no tienen folio.";
							$incidencia->id_pro = $procesoNuevo;
							$incidencia->id_es = $s->id_es;
							$incidencia->save();

							DB::table("pago")
							->where('id_pago', '=', $s->id_pago)
							->update(["timbrado" => "Los documentos referenciados de este pago no tienen folio."]);
			    		}
			    	}
			    	else{
			    		//No existe RFC o nombre de cliente en pago de tesorer??a, se va a incidencia
				    	$incidencia = new Incidencias;
				    	$incidencia->id_pago = $s->id_pago;
						$incidencia->clearing_document = $s->clearing_document;
						$incidencia->version = $s->version;
						$incidencia->fecha_clearing = $s->fecha_clearing;
						$incidencia->regimen = $s->regimen;
						$incidencia->lugarexpedicion = $s->lugarexpedicion;
						$incidencia->residenciafiscal = $s->residenciafiscal;
						$incidencia->numregidtrib = $s->numregidtrib;
						$incidencia->confirmacion = $s->confirmacion;
						$incidencia->formap = $s->formap;
						$incidencia->monedaP = $s->monedaP;
						$incidencia->fechap = $s->fechap;
						$incidencia->tipocambioP = $s->tipocambioP;
						$incidencia->montoP = $s->montoP;
						$incidencia->signo = $s->signo;
						$incidencia->numeroperP = $s->numeroperP;
						$incidencia->rfcctaord = $s->rfcctaord;
						$incidencia->bancoordext = $s->bancoordext;
						$incidencia->ctaord = $s->ctaord;
						$incidencia->rfcctaben = $s->rfcctaben;
						$incidencia->cataben = $s->cataben;
						$incidencia->rfc_c = $s->rfc_c;
						$incidencia->nombre_c = $s->nombre_c;
						$incidencia->rfc_e = $s->rfc_e;
						$incidencia->nombre_e = $s->nombre_e;
						$incidencia->id_cliente = $s->id_cliente;
						$incidencia->timbrado = "El cliente no fue especificado en el pago o no existe.";
						$incidencia->id_pro = $procesoNuevo;
						$incidencia->id_es = $s->id_es;
						$incidencia->save();

						DB::table("pago")
						->where('id_pago', '=', $s->id_pago)
						->update(["timbrado" => "El cliente no fue especificado en el pago o no existe."]);
			    	}
			}
			

		    $refer = DB::table("pago")
		    ->where("timbrado", "=", "No existe relaci??n de montos y monedas entre tesoreria y SAP")
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->get();

		    $bancosSAP = DB::table("bancos_SAP")
		    ->get();

		    foreach ($refer as $r) {
		    	$ex = false;
		    	$monto = "";
		    	foreach ($bancosSAP as $b) {
		    		if($r->clearing_document == $b->clearing_document){
		    			$ex = true;
		    			if($r->monedaP != "MXN"){
		    				$monto = $b->monto;
		    			}
		    			else{
		    				$monto = $b->montomxn;
		    			}
		    			break;
		    		}
		    	}

		    	if($ex == true){
		    		$banco = explode(" ", $r->reference);
			    	if($banco[0] == "JPMUSUSD"){

			    		$cfdirels = DB::table("credito")
						->where('clearing_document', '=', $r->clearing_document)
						->whereRaw(DB::raw('('.$busquedaCred.')'))
						->count();

				    	if($cfdirels > 0){
						    		$cfdirels = DB::table("credito")
						    		->where('clearing_document', '=', $r->clearing_document)
						    		->whereRaw(DB::raw('('.$busquedaCred.')'))
						    		->get();

						    		$repetidos = false;

						    		foreach ($cfdirels as $cfdi) {
						    			$solo_uno = DB::table('credito')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaCred.')'))
							    			->count();

							    			if($solo_uno > 1){
							    				$solo_uno = DB::table('credito')
							    				->where('folio', '=', $cfdi->folio)
							    				->where('imppagado', '=', $cfdi->imppagado)
							    				->where('clearing_document', '=', $cfdi->clearing_document)
							    				->whereRaw(DB::raw('('.$busquedaCred.')'))
							    				->delete();

							    				$solo_uno = DB::table('credito')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaCred.')'))
								    			->count();

							    				if($solo_uno > 1){
							    					$repetidos = true;
							    				}
							    			}
						    		}

						    		if($repetidos == false){
						    			$fechaar = str_replace(".", "", $r->fechadoc);
							    		$fechaar = $fechaar."120000";
							    		DB::table("pago")
							    		->where("id_pago", "=", $r->id_pago)
							    		->update([
							    			"formap" => "03",
							    			"fechap" => $fechaar,
							    			"timbrado" => "Se obtuvieron los datos de SAP",
							    		]);

							    		$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.",";
									    		}
								    		}
						    			}

							    		if($saldadoCredito == false && $saldadoLiquidadas == false){
							    			$complemento = new Complemento;
							    			$complemento->id_pago = $r->id_pago;
										    $complemento->clearing_document = $r->clearing_document;
											$complemento->version = $r->version;
											$complemento->fecha_clearing = $r->fecha_clearing;
											$complemento->regimen = $r->regimen;
											$complemento->lugarexpedicion = $r->lugarexpedicion;
											$complemento->residenciafiscal = $r->residenciafiscal;
											$complemento->numregidtrib = $r->numregidtrib;
											$complemento->confirmacion = $r->confirmacion;
											$complemento->formap = "03";
											$complemento->monedaP = $r->monedaP;
											$complemento->fechap = $fechaar;
											$complemento->fechabus = $r->fechabus;
											$complemento->tipocambioP = $r->tipocambioP;
											$complemento->montoP = $monto;
											$complemento->signo = $r->signo;
											$complemento->numeroperP = "";
											$complemento->rfcctaord = "";
											$complemento->bancoordext = "";
											$complemento->ctaord = "";
											$complemento->rfcctaben = "";
											$complemento->cataben = "0700626190";
											$complemento->rfc_c = $r->rfc_c;
											$complemento->nombre_c = $r->nombre_c;
											$complemento->rfc_e = $r->rfc_e;
											$complemento->nombre_e = $r->nombre_e;
											$complemento->id_cliente = $r->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $r->id_es;
											$complemento->save();

											DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->delete();
							    		}
							    		else{
							    			DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("similaridades")
											->delete();
							    		}
						    		}
						    		else{
						    			DB::table("incidencias")
										->where('id_pago', '=', $r->id_pago)
										->where('id_pro', '=', $procesoNuevo)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("pago")
										->where('id_pago', '=', $r->id_pago)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("similaridades")
										->delete();
						    		}
				    	}
				    	else{
				    		$liquidadas = DB::table("facturas_liquidadas")
						    ->where('clearing_document', '=', $r->clearing_document)
						    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    ->count();

						    if($liquidadas > 0){
						    	$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			if($saldadoCredito == false && $saldadoLiquidadas == false){
						    		$complemento = new Complemento;
							    	$complemento->id_pago = $r->id_pago;
									$complemento->clearing_document = $r->clearing_document;
									$complemento->version = $r->version;
									$complemento->fecha_clearing = $r->fecha_clearing;
									$complemento->regimen = $r->regimen;
									$complemento->lugarexpedicion = $r->lugarexpedicion;
									$complemento->residenciafiscal = $r->residenciafiscal;
									$complemento->numregidtrib = $r->numregidtrib;
									$complemento->confirmacion = $r->confirmacion;
									$complemento->formap = "03";
									$complemento->monedaP = $r->monedaP;
									$complemento->fechap = $fechaar;
									$complemento->fechabus = $r->fechabus;
									$complemento->tipocambioP = $r->tipocambioP;
									$complemento->montoP = $monto;
									$complemento->signo = $r->signo;
									$complemento->numeroperP = "";
									$complemento->rfcctaord = "";
									$complemento->bancoordext = "";
									$complemento->ctaord = "";
									$complemento->rfcctaben = "";
									$complemento->cataben = "0700626190";
									$complemento->rfc_c = $r->rfc_c;
									$complemento->nombre_c = $r->nombre_c;
									$complemento->rfc_e = $r->rfc_e;
									$complemento->nombre_e = $r->nombre_e;
									$complemento->id_cliente = $r->id_cliente;
									$complemento->timbrado = "1";
									$complemento->id_pro = $procesoNuevo;
									$complemento->id_es = $r->id_es;
									$complemento->save();

									DB::table("incidencias")
									->where('id_pago', '=', $r->id_pago)
									->where('id_pro', '=', $procesoNuevo)
									->delete();
						    	}
						    	else{
						    		DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("similaridades")
											->delete();
						    	}
						    }

						    else{
						    	DB::table("incidencias")
								->where('id_pago', '=', $r->id_pago)
								->where('id_pro', '=', $procesoNuevo)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("pago")
								->where('id_pago', '=', $r->id_pago)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("similaridades")
								->delete();
						    }
				    	}


			    	}
			    	else{
			    		if($r->nombre_c == "ARVATO DE MEXICO, S.A. DE C.V." || $r->nombre_c == "QUIMICA ONTARIO, S.A. DE C.V." || $r->nombre_c == "CALZADO CHAVITA,  S.A. DE C.V." || $r->nombre_c == "INDUSTRIAS SYLPYL, S.A. DE C.V." || $r->nombre_c == "INDUSTRIAL DE PINTURAS ECATEPEC, S.A. DE C.V." || $r->nombre_c == "DURAN CHEMICALS, S.A. DE C.V." || $r->nombre_c == "COMEX INDUSTRIAL COATINGS, S.A. DE C.V." || $r->nombre_c == "FABRICA DE PINTURAS UNIVERSALES, S.A. DE C.V."|| $r->nombre_c == "FXI DE CUAUTITLAN, S.A. DE C.V." || $r->nombre_c == "PROVEEDURIA INTERNACIONAL DE LEON, S.A. DE C.V."|| $r->nombre_c == "PRODUCTOS RIVIAL, S.A. DE C.V."|| $r->nombre_c == "Manufacturera de Calzado PMA S.A. d" ){
			    			$cfdirels = DB::table("credito")
							->where('clearing_document', '=', $r->clearing_document)
							->count();

					    	if($cfdirels > 0){
							    		$cfdirels = DB::table("credito")
							    		->where('clearing_document', '=', $r->clearing_document)
							    		->whereRaw(DB::raw('('.$busquedaCred.')'))
							    		->get();

							    		$repetidos = false;

							    		foreach ($cfdirels as $cfdi) {
							    			$solo_uno = DB::table('credito')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaCred.')'))
								    			->count();

								    			if($solo_uno > 1){
								    				$solo_uno = DB::table('credito')
								    				->where('folio', '=', $cfdi->folio)
								    				->where('imppagado', '=', $cfdi->imppagado)
								    				->where('clearing_document', '=', $cfdi->clearing_document)
								    				->whereRaw(DB::raw('('.$busquedaCred.')'))
								    				->delete();

								    				$solo_uno = DB::table('credito')
									    			->where('folio', '=', $cfdi->folio)
									    			->where('clearing_document', '=', $cfdi->clearing_document)
									    			->whereRaw(DB::raw('('.$busquedaCred.')'))
									    			->count();

								    				if($solo_uno > 1){
								    					$repetidos = true;
								    				}
								    			}
							    		}

							    		if($repetidos == false){
							    			$saldadoCredito = false;
							    			$numSalCred = "";
							    			$saldadoLiquidadas = false;
							    			$numSalLiqu = "";
							    			foreach ($cfdirels as $cfdi) {
							    				$ultimosC = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->count();
									    		if($ultimosC > 0){
									    			$ultimoCredito = DB::table("credito")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->orderBy("id_cre", "desc")
										    		->first();
										    		if($ultimoCredito->impsaldoins == 0){
										    			$saldadoCredito = true;
										    			$numSalCred .= $cfdi->folio.",";
										    		}
									    		}
							    			}
							    			foreach ($cfdirels as $cfdi) {
							    				$ultimosL = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->count();
									    		if($ultimosL > 0){
									    			$ultimoLiquidada = DB::table("facturas_liquidadas")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->orderBy("id_cre", "desc")
										    		->first();
										    		if($ultimoLiquidada->impsaldoins == 0){
										    			$saldadoLiquidadas = true;
										    			$numSalLiqu .= $cfdi->folio.",";
										    		}
									    		}
							    			}
							    			if($saldadoCredito == false && $saldadoLiquidadas == false){
							    				$fechaar = str_replace(".", "", $r->fechadoc);
								    			$fechaar = $fechaar."120000";
								    			DB::table("pago")
									    		->where("id_pago", "=", $r->id_pago)
									    		->update([
									    			"formap" => "02",
									    			"fechap" => $fechaar,
									    			"timbrado" => "Se obtuvieron los datos de SAP",
									    		]);

									    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "02";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BBA830831LJ2";
														$complemento->cataben = "0199889965";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}

									    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "02";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BBA830831LJ2";
														$complemento->cataben = "0199073027";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}
									    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "02";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BSM970519DU8";
														$complemento->cataben = "82500849779";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}
									    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "02";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BSM970519DU8";
														$complemento->cataben = "65505095178";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}

												else{
													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

													DB::table("pago")
													->where('id_pago', '=', $r->id_pago)
													->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
												}
							    			}
							    			else{
							    				DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

												DB::table("similaridades")
												->delete();
							    			}
							    		}
							    		else{
							    			DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

											DB::table("similaridades")
											->delete();
							    		}
					    	}
					    	else{
					    		$liquidadas = DB::table("facturas_liquidadas")
							    ->where('clearing_document', '=', $r->clearing_document)
							    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    ->count();

				    			if($liquidadas > 0){
				    				$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			if($saldadoCredito == false && $saldadoLiquidadas == false){
							    			$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "02",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where("id_inci", '=', $r->id_pago)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "82500849779";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "65505095178";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

											else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
											}
				    					}
					    				else{
					    					DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

											DB::table("similaridades")
											->delete();
					    				}
				    			}

				    			else{
				    				DB::table("incidencias")
									->where('id_pago', '=', $r->id_pago)
									->where('id_pro', '=', $procesoNuevo)
									->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

									DB::table("pago")
									->where('id_pago', '=', $r->id_pago)
									->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 4"]);

									DB::table("similaridades")
									->delete();
				    			}
				    		}
			    		}
			    		else{
			    			$cfdirels = DB::table("credito")
							->where('clearing_document', '=', $r->clearing_document)
							->whereRaw(DB::raw('('.$busquedaCred.')'))
							->count();

			    			if($cfdirels > 0){
							    		$cfdirels = DB::table("credito")
							    		->where('clearing_document', '=', $r->clearing_document)
							    		->whereRaw(DB::raw('('.$busquedaCred.')'))
							    		->get();

							    		$repetidos = false;

							    		foreach ($cfdirels as $cfdi) {
							    			$solo_uno = DB::table('credito')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaCred.')'))
								    			->count();

								    			if($solo_uno > 1){
								    				$solo_uno = DB::table('credito')
								    				->where('folio', '=', $cfdi->folio)
								    				->where('imppagado', '=', $cfdi->imppagado)
								    				->where('clearing_document', '=', $cfdi->clearing_document)
								    				->whereRaw(DB::raw('('.$busquedaCred.')'))
								    				->delete();

								    				$solo_uno = DB::table('credito')
									    			->where('folio', '=', $cfdi->folio)
									    			->where('clearing_document', '=', $cfdi->clearing_document)
									    			->whereRaw(DB::raw('('.$busquedaCred.')'))
									    			->count();

								    				if($solo_uno > 1){
								    					$repetidos = true;
								    				}
								    			}
							    		}

							    		if($repetidos == false){
							    			$saldadoCredito = false;
							    			$numSalCred = "";
							    			$saldadoLiquidadas = false;
							    			$numSalLiqu = "";
							    			foreach ($cfdirels as $cfdi) {
							    				$ultimosC = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->count();
									    		if($ultimosC > 0){
									    			$ultimoCredito = DB::table("credito")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->orderBy("id_cre", "desc")
										    		->first();
										    		if($ultimoCredito->impsaldoins == 0){
										    			$saldadoCredito = true;
										    			$numSalCred .= $cfdi->folio.",";
										    		}
									    		}
							    			}
							    			foreach ($cfdirels as $cfdi) {
							    				$ultimosL = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->count();
									    		if($ultimosL > 0){
									    			$ultimoLiquidada = DB::table("facturas_liquidadas")
										    		->where('clearing_document', '<', $s->clearing_document)
										    		->where('folio', '=', $cfdi->folio)
										    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
										    		->orderBy("id_cre", "desc")
										    		->first();
										    		if($ultimoLiquidada->impsaldoins == 0){
										    			$saldadoLiquidadas = true;
										    			$numSalLiqu .= $cfdi->folio.",";
										    		}
									    		}
							    			}
							    			if($saldadoCredito == false && $saldadoLiquidadas == false){
								    			$fechaar = str_replace(".", "", $r->fechadoc);
								    			$fechaar = $fechaar."120000";
								    			DB::table("pago")
									    		->where("id_pago", "=", $r->id_pago)
									    		->update([
									    			"formap" => "03",
									    			"fechap" => $fechaar,
									    			"timbrado" => "Se obtuvieron los datos de SAP",
									    		]);

									    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "03";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BBA830831LJ2";
														$complemento->cataben = "0199889965";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}

									    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "03";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BBA830831LJ2";
														$complemento->cataben = "0199073027";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}
									    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "03";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BSM970519DU8";
														$complemento->cataben = "014180825008497793";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}
									    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
									    			$complemento = new Complemento;
									    			$complemento->id_pago = $r->id_pago;
													    $complemento->clearing_document = $r->clearing_document;
														$complemento->version = $r->version;
														$complemento->fecha_clearing = $r->fecha_clearing;
														$complemento->regimen = $r->regimen;
														$complemento->lugarexpedicion = $r->lugarexpedicion;
														$complemento->residenciafiscal = $r->residenciafiscal;
														$complemento->numregidtrib = $r->numregidtrib;
														$complemento->confirmacion = $r->confirmacion;
														$complemento->formap = "03";
														$complemento->monedaP = $r->monedaP;
														$complemento->fechap = $fechaar;
														$complemento->fechabus = $r->fechabus;
														$complemento->tipocambioP = $r->tipocambioP;
														$complemento->montoP = $monto;
														$complemento->signo = $r->signo;
														$complemento->numeroperP = "";
														$complemento->rfcctaord = "";
														$complemento->bancoordext = "";
														$complemento->ctaord = "";
														$complemento->rfcctaben = "BSM970519DU8";
														$complemento->cataben = "014180655050951787";
														$complemento->rfc_c = $r->rfc_c;
														$complemento->nombre_c = $r->nombre_c;
														$complemento->rfc_e = $r->rfc_e;
														$complemento->nombre_e = $r->nombre_e;
														$complemento->id_cliente = $r->id_cliente;
														$complemento->timbrado = "1";
														$complemento->id_pro = $procesoNuevo;
														$complemento->id_es = $r->id_es;
														$complemento->save();

														DB::table("incidencias")
														->where('id_pago', '=', $r->id_pago)
														->where('id_pro', '=', $procesoNuevo)
														->delete();
									    		}

									    		else{
													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

													DB::table("pago")
													->where('id_pago', '=', $r->id_pago)
													->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
												}
							    			}
							    			else{
							    				DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

												DB::table("similaridades")
												->delete();
							    			}
							    		}
							    		else{
							    			DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

											DB::table("similaridades")
											->delete();
							    		}
					    	}
					    	else{
					    		$liquidadas = DB::table("facturas_liquidadas")
							    ->where('clearing_document', '=', $r->clearing_document)
							    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    ->count();

				    			if($liquidadas > 0){
				    					$saldadoCredito = false;
						    			$numSalCred = "";
						    			$saldadoLiquidadas = false;
						    			$numSalLiqu = "";
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosC = DB::table("credito")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosC > 0){
								    			$ultimoCredito = DB::table("credito")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoCredito->impsaldoins == 0){
									    			$saldadoCredito = true;
									    			$numSalCred .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			foreach ($liquidadas as $cfdi) {
						    				$ultimosL = DB::table("facturas_liquidadas")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosL > 0){
								    			$ultimoLiquidada = DB::table("facturas_liquidadas")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_cre", "desc")
									    		->first();
									    		if($ultimoLiquidada->impsaldoins == 0){
									    			$saldadoLiquidadas = true;
									    			$numSalLiqu .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			if($saldadoCredito == false && $saldadoLiquidadas == false){
							    			$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "03",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "014180825008497793";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $monto;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "014180655050951787";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $r->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

											else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP 23"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP 23"]);
											}
				    					}
				    				else{
				    					DB::table("incidencias")
										->where('id_pago', '=', $r->id_pago)
										->where('id_pro', '=', $procesoNuevo)
										->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

										DB::table("pago")
										->where('id_pago', '=', $r->id_pago)
										->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalCred.$numSalLiqu]);

										DB::table("similaridades")
										->delete();
				    				}
				    			}

				    			else{
				    				DB::table("incidencias")
									->where('id_pago', '=', $r->id_pago)
									->where('id_pro', '=', $procesoNuevo)
									->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

									DB::table("pago")
									->where('id_pago', '=', $r->id_pago)
									->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 4"]);

									DB::table("similaridades")
									->delete();
				    			}
				    		}
			    		}
			    	}
		    	}
		    }

		    $arrow = DB::table("pago")
		    ->select('clearing_document')
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->get();

		    $array[0] = "";
		    $a = 0;

		    foreach ($arrow as $c7) {
		    	$array[$a] = $c7->clearing_document;
		    	$a++;
		    }

		    $existe = DB::table('parcialidades')
		    ->select('clearing_document', 'id_es')
		    ->whereNotIn("clearing_document", $array)
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->groupBy('clearing_document', 'id_es')
		    ->get();

		    foreach ($existe as $e) {
		    	$totalidad = DB::table("parcialidades")
		    	->where("clearing_document", "=", $e->clearing_document)
		    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    	->get();

		    	$total_pag = 0;

		    	foreach ($totalidad as $total) {
		    		$total_pag = $total_pag + (float)$total->imppagado;
		    	}

		    	$pari = DB::table("parcialidades")
		    	->where("clearing_document", "=", $e->clearing_document)
		    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    	->first();

		    	$pago = DB::table('pago')->insertGetId([
		    		"clearing_document" => $e->clearing_document,
						"version" => "1",
						"fecha_clearing" => "",
						"regimen" => "601",
						"lugarexpedicion" => "",
						"residenciafiscal" => "",
						"numregidtrib" => "",
						"confirmacion" => "",
						"formap" => "25",
						"monedaP" => $pari->moneda,
						"fechap" => "",
						"fechadoc" => "",
						"assignment" => "",
						"reference" => "",
						"tipocambioP" => $pari->tipcambio,
						"signo" => "+",
						"montoP" => $total_pag,
						"numeroperP" => "",
						"rfcctaord" => "",
						"bancoordext" => "",
						"ctaord" => "",
						"cataben" => "",
						"rfc_c" => $pari->rfc_c,
						"nombre_c" => $pari->nombre_c,
						"id_cliente" => "",
						"rfc_e" => "",
						"nombre_e" => "",
						"timbrado" => "Es pago con clearing 701",
						"id_es" => $e->id_es,
		    	]);

		    	$r = DB::table("pago")
		    	->where("id_pago", "=", $pago)
		    	->first();

						$complemento = new Complemento;
							    			$complemento->id_pago = $r->id_pago;
											    $complemento->clearing_document = $r->clearing_document;
												$complemento->version = $r->version;
												$complemento->fecha_clearing = $r->fecha_clearing;
												$complemento->regimen = $r->regimen;
												$complemento->lugarexpedicion = $r->lugarexpedicion;
												$complemento->residenciafiscal = $r->residenciafiscal;
												$complemento->numregidtrib = $r->numregidtrib;
												$complemento->confirmacion = $r->confirmacion;
												$complemento->formap = $r->formap;
												$complemento->monedaP = $r->monedaP;
												$complemento->fechap = "";
												$complemento->tipocambioP = $r->tipocambioP;
												$complemento->montoP = $monto;
												$complemento->signo = $r->signo;
												$complemento->numeroperP = "";
												$complemento->rfcctaord = "";
												$complemento->bancoordext = "";
												$complemento->ctaord = "";
												$complemento->rfcctaben = "";
												$complemento->cataben = "";
												$complemento->rfc_c = $r->rfc_c;
												$complemento->nombre_c = $r->nombre_c;
												$complemento->rfc_e = $r->rfc_e;
												$complemento->nombre_e = $r->nombre_e;
												$complemento->timbrado = "1";
												$complemento->id_pro = $procesoNuevo;
												$complemento->save();
		    }
		}
		else{
			foreach ($sap as $s){
		    	if($s->reference == "Remisi??n" || $s->reference == "remisi??n" || $s->formap == "17" || $s->formap == 17){
		    				$fechaar = str_replace(".", "", $s->fechadoc);
						    			$fechaar = $fechaar."120000";
					    			$complemento = new Complemento;
						    		$complemento->id_pago = $s->id_pago;
								    $complemento->clearing_document = $s->clearing_document;
									$complemento->version = $s->version;
									$complemento->fecha_clearing = $s->fecha_clearing;
									$complemento->regimen = $s->regimen;
									$complemento->lugarexpedicion = $s->lugarexpedicion;
									$complemento->residenciafiscal = $s->residenciafiscal;
									$complemento->numregidtrib = $s->numregidtrib;
									$complemento->confirmacion = $s->confirmacion;
									if($s->reference == "Remisi??n" || $s->reference == "remisi??n"){
										$complemento->formap = "25";
									}
									else{
										$complemento->formap = $s->formap;
									}
									$complemento->monedaP = $s->monedaP;
									$complemento->fechap = $fechaar;
									$complemento->fechabus = $s->fechabus;
									$complemento->tipocambioP = $s->tipocambioP;
									$complemento->montoP = $s->montoP;
									$complemento->signo = $s->signo;
									$complemento->numeroperP = "";
									$complemento->rfcctaord = "";
									$complemento->bancoordext = "";
									$complemento->ctaord = "";
									$complemento->rfcctaben = "";
									$complemento->cataben = "";
									$complemento->rfc_c = $s->rfc_c;
									$complemento->nombre_c = $s->nombre_c;
									$complemento->rfc_e = $s->rfc_e;
									$complemento->nombre_e = $s->nombre_e;
									$complemento->id_cliente = $s->id_cliente;
									$complemento->timbrado = "1";
									$complemento->id_pro = $procesoNuevo;
									$complemento->id_es = $s->id_es;
									$complemento->save();

									DB::table("pago")
									->where('id_pago', '=', $s->id_pago)
									->update(["timbrado" => "1"]);
					    }
		    	elseif ($s->rfc_c != ""){
		    		$documents = DB::table("parcialidades")
		    		->where("clearing_document", "=", $s->clearing_document)
		    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    		->get();

		    		$refer_documents = false;

		    		foreach ($documents as $doc) {
		    			if($doc->folio != 0 && $doc->folio != "0" && $doc->folio != "" && !is_null($doc->folio) && $doc->folio != "0" && $doc->folio != "#"){

		    			}
		    			else{
		    				$refer_documents = true;
		    			}
		    		}

		    		if($refer_documents == false){

			    		//Existe RFC o nombre de cliente en pago de SAP
			    		$tesoreria = DB::table("tesoreria")
						->whereRaw(DB::raw('('.$busquedaTeso.')'))
						->get();

			    		foreach ($tesoreria as $teso){
			    			if($teso->RFC_R != "" && !is_null($teso->RFC_R)){//Existe cliente en pago de tesoreria, ya sea RFC o nombre del cliente
			    				if($s->rfc_c == $teso->RFC_R){//Coincide el RFC, se va a comparar monto y moneda
				    				if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
				    					if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
											$simi2 = DB::table('similaridades')
    										->get();
											foreach($simi2 as $sim){
												if($sim->NUMEROPERP != $teso->NUMEROPERP){
													$simi = new Similaridades;
													$simi->id_tt = $teso->id_tt;
													$simi->RFC_R = $teso->RFC_R;
													$simi->MONTOP = $teso->MONTOP;
													$simi->MONEDAP = $teso->MONEDAP;
													$simi->NUMEROPERP = $teso->NUMEROPERP;
													$simi->RFCCTABEN = $teso->RFCCTABEN;
													$simi->CATABEN = $teso->CATABEN;
													$simi->FORMAP = $teso->FORMAP;
													$simi->RFCCTAORD = $teso->RFCCTAORD;
													$simi->BANCOORDEXT = $teso->BANCOORDEXT;
													$simi->CTAORD = $teso->CTAORD;
													$simi->FECHAPAG = $teso->FECHAPAG;
													$simi->save();
												}
											}
				    					}
				    				}
				    			}
				    			else{
				    				if($s->nombre_c == $teso->RFC_R){//Coincide Nombre, se va a comparar monto y moneda.
				    					if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
				    						if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
												$simi2 = DB::table('similaridades')
												->get();
												foreach($simi2 as $sim){
													if($sim->NUMEROPERP != $teso->NUMEROPERP){
														$simi = new Similaridades;
														$simi->id_tt = $teso->id_tt;
														$simi->RFC_R = $teso->RFC_R;
														$simi->MONTOP = $teso->MONTOP;
														$simi->MONEDAP = $teso->MONEDAP;
														$simi->NUMEROPERP = $teso->NUMEROPERP;
														$simi->RFCCTABEN = $teso->RFCCTABEN;
														$simi->CATABEN = $teso->CATABEN;
														$simi->FORMAP = $teso->FORMAP;
														$simi->RFCCTAORD = $teso->RFCCTAORD;
														$simi->BANCOORDEXT = $teso->BANCOORDEXT;
														$simi->CTAORD = $teso->CTAORD;
														$simi->FECHAPAG = $teso->FECHAPAG;
														$simi->save();
													}
												}
					    					}
					    				}
				    				}
				    			}
			    			}
			    			else{//No existe en Tesoreria nombre o RFC, se procede a comparar monto y moneda.
			    				if($s->montoP == $teso->MONTOP){//Coinciden los montos, se va a comparar moneda
				    				if($s->monedaP == $teso->MONEDAP){//Coincide la moneda. Se va a similaridades.
										$simi2 = DB::table('similaridades')
    									->get();
										foreach($simi2 as $sim){
											if($sim->NUMEROPERP != $teso->NUMEROPERP){
												$simi = new Similaridades;
												$simi->id_tt = $teso->id_tt;
												$simi->RFC_R = $teso->RFC_R;
												$simi->MONTOP = $teso->MONTOP;
												$simi->MONEDAP = $teso->MONEDAP;
												$simi->NUMEROPERP = $teso->NUMEROPERP;
												$simi->RFCCTABEN = $teso->RFCCTABEN;
												$simi->CATABEN = $teso->CATABEN;
												$simi->FORMAP = $teso->FORMAP;
												$simi->RFCCTAORD = $teso->RFCCTAORD;
												$simi->BANCOORDEXT = $teso->BANCOORDEXT;
												$simi->CTAORD = $teso->CTAORD;
												$simi->FECHAPAG = $teso->FECHAPAG;
												$simi->save();
											}
										}
					    			}
					    			else{
						    			$res = "Las monedas no coinciden";
						    		}
					    		}
					    		else{
					    			$res = "Los montos no coinciden";
					    		}
			    			}
			    		}

			    		$conteo = DB::table("similaridades")
					    ->count();

					    $simil = DB::table("similaridades")
					    ->get();

					    if($conteo == 0){
					    	$incidencia = new Incidencias;
					    	$incidencia->id_pago = $s->id_pago;
						    $incidencia->clearing_document = $s->clearing_document;
							$incidencia->version = $s->version;
							$incidencia->fecha_clearing = $s->fecha_clearing;
							$incidencia->regimen = $s->regimen;
							$incidencia->lugarexpedicion = $s->lugarexpedicion;
							$incidencia->residenciafiscal = $s->residenciafiscal;
							$incidencia->numregidtrib = $s->numregidtrib;
							$incidencia->confirmacion = $s->confirmacion;
							$incidencia->formap = $s->formap;
							$incidencia->monedaP = $s->monedaP;
							$incidencia->fechap = $s->fechap;
							$incidencia->tipocambioP = $s->tipocambioP;
							$incidencia->montoP = $s->montoP;
							$incidencia->signo = $s->signo;
							$incidencia->numeroperP = $s->numeroperP;
							$incidencia->rfcctaord = $s->rfcctaord;
							$incidencia->bancoordext = $s->bancoordext;
							$incidencia->ctaord = $s->ctaord;
							$incidencia->rfcctaben = $s->rfcctaben;
							$incidencia->cataben = $s->cataben;
							$incidencia->rfc_c = $s->rfc_c;
							$incidencia->nombre_c = $s->nombre_c;
							$incidencia->rfc_e = $s->rfc_e;
							$incidencia->nombre_e = $s->nombre_e;
							$incidencia->id_cliente = $s->id_cliente;
							$incidencia->timbrado = "No existe relaci??n de montos y monedas entre tesoreria y SAP";
							$incidencia->id_pro = $procesoNuevo;
							$incidencia->id_es = $s->id_es;
							$incidencia->save();

							DB::table("pago")
							->where('clearing_document', '=', $s->clearing_document)
							->whereRaw(DB::raw('('.$busquedaSAP.')'))
							->update(["timbrado" => "No existe relaci??n de montos y monedas entre tesoreria y SAP"]);
					    }
					    elseif($conteo == 1){
					    	$cfdirels = DB::table("parcialidades")
					    	->where('clearing_document', '=', $s->clearing_document)
					    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
					    	->count();

					    	if($cfdirels > 0){
					    		$cfdirels = DB::table("parcialidades")
					    		->where('clearing_document', '=', $s->clearing_document)
					    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
					    		->get();

					    		$repetidos = false;

					    		foreach ($cfdirels as $cfdi) {
					    			$solo_uno = DB::table('parcialidades')
						    			->where('folio', '=', $cfdi->folio)
						    			->where('clearing_document', '=', $cfdi->clearing_document)
						    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    			->count();

						    			if($solo_uno > 1){
						    				$solo_uno = DB::table('parcialidades')
						    				->where('folio', '=', $cfdi->folio)
						    				->where('imppagado', '=', $cfdi->imppagado)
						    				->where('clearing_document', '=', $cfdi->clearing_document)
						    				->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    				->delete();

						    				$solo_uno = DB::table('parcialidades')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    			->count();

						    				if($solo_uno > 1){
						    					$repetidos = true;
						    				}
						    			}
					    		}

					    		if($repetidos == false){
					    				$saldadoParcialidades = false;
						    			$numSalParc = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosP = DB::table("parcialidades")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosP > 0){
								    			$ultimoParcial = DB::table("parcialidades")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_par", "desc")
									    		->first();
									    		if($ultimoParcial->impsaldoins == 0){
									    			$saldadoParcialidades= true;
									    			$numSalParc .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			

					    				if($saldadoParcialidades == false){
					    					$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('id_pago', '=', $s->id_pago)
											->update(["timbrado" => "1"]);

											DB::table("tesoreria")
											->where('id_tt', '=', $simil[0]->id_tt)
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
					    				}
					    				else{
					    					$incidencia = new Incidencias;
								    		$incidencia->id_pago = $s->id_pago;
										    $incidencia->clearing_document = $s->clearing_document;
											$incidencia->version = $s->version;
											$incidencia->fecha_clearing = $s->fecha_clearing;
											$incidencia->regimen = $s->regimen;
											$incidencia->lugarexpedicion = $s->lugarexpedicion;
											$incidencia->residenciafiscal = $s->residenciafiscal;
											$incidencia->numregidtrib = $s->numregidtrib;
											$incidencia->confirmacion = $s->confirmacion;
											$incidencia->formap = $s->formap;
											$incidencia->monedaP = $s->monedaP;
											$incidencia->fechap = $s->fechap;
											$incidencia->tipocambioP = $s->tipocambioP;
											$incidencia->montoP = $s->montoP;
											$incidencia->signo = $s->signo;
											$incidencia->numeroperP = $s->numeroperP;
											$incidencia->rfcctaord = $s->rfcctaord;
											$incidencia->bancoordext = $s->bancoordext;
											$incidencia->ctaord = $s->ctaord;
											$incidencia->rfcctaben = $s->rfcctaben;
											$incidencia->cataben = $s->cataben;
											$incidencia->rfc_c = $s->rfc_c;
											$incidencia->nombre_c = $s->nombre_c;
											$incidencia->rfc_e = $s->rfc_e;
											$incidencia->nombre_e = $s->nombre_e;
											$incidencia->id_cliente = $s->id_cliente;
											$incidencia->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
											$incidencia->id_pro = $procesoNuevo;
											$incidencia->id_es = $s->id_es;
											$incidencia->save();

											DB::table("pago")
											->where('clearing_document', '=', $s->clearing_document)
											->whereRaw(DB::raw('('.$busquedaSAP.')'))
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalParc]);

											DB::table("similaridades")
											->delete();
					    				}
					    		}
					    		else{
					    			$incidencia = new Incidencias;
						    		$incidencia->id_pago = $s->id_pago;
								    $incidencia->clearing_document = $s->clearing_document;
									$incidencia->version = $s->version;
									$incidencia->fecha_clearing = $s->fecha_clearing;
									$incidencia->regimen = $s->regimen;
									$incidencia->lugarexpedicion = $s->lugarexpedicion;
									$incidencia->residenciafiscal = $s->residenciafiscal;
									$incidencia->numregidtrib = $s->numregidtrib;
									$incidencia->confirmacion = $s->confirmacion;
									$incidencia->formap = $s->formap;
									$incidencia->monedaP = $s->monedaP;
									$incidencia->fechap = $s->fechap;
									$incidencia->tipocambioP = $s->tipocambioP;
									$incidencia->montoP = $s->montoP;
									$incidencia->signo = $s->signo;
									$incidencia->numeroperP = $s->numeroperP;
									$incidencia->rfcctaord = $s->rfcctaord;
									$incidencia->bancoordext = $s->bancoordext;
									$incidencia->ctaord = $s->ctaord;
									$incidencia->rfcctaben = $s->rfcctaben;
									$incidencia->cataben = $s->cataben;
									$incidencia->rfc_c = $s->rfc_c;
									$incidencia->nombre_c = $s->nombre_c;
									$incidencia->rfc_e = $s->rfc_e;
									$incidencia->nombre_e = $s->nombre_e;
									$incidencia->id_cliente = $s->id_cliente;
									$incidencia->timbrado = "Se ha registrado un folio mas de una vez, en este pago.";
									$incidencia->id_pro = $procesoNuevo;
									$incidencia->id_es = $s->id_es;
									$incidencia->save();

									DB::table("pago")
									->where('id_pago', '=', $s->id_pago)
									->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

									DB::table("similaridades")
									->delete();
					    		}
					    	}

					    	else{
					    		$incidencia = new Incidencias;
					    		$incidencia->id_pago = $s->id_pago;
							    $incidencia->clearing_document = $s->clearing_document;
								$incidencia->version = $s->version;
								$incidencia->fecha_clearing = $s->fecha_clearing;
								$incidencia->regimen = $s->regimen;
								$incidencia->lugarexpedicion = $s->lugarexpedicion;
								$incidencia->residenciafiscal = $s->residenciafiscal;
								$incidencia->numregidtrib = $s->numregidtrib;
								$incidencia->confirmacion = $s->confirmacion;
								$incidencia->formap = $s->formap;
								$incidencia->monedaP = $s->monedaP;
								$incidencia->fechap = $s->fechap;
								$incidencia->tipocambioP = $s->tipocambioP;
								$incidencia->montoP = $s->montoP;
								$incidencia->signo = $s->signo;
								$incidencia->numeroperP = $s->numeroperP;
								$incidencia->rfcctaord = $s->rfcctaord;
								$incidencia->bancoordext = $s->bancoordext;
								$incidencia->ctaord = $s->ctaord;
								$incidencia->rfcctaben = $s->rfcctaben;
								$incidencia->cataben = $s->cataben;
								$incidencia->rfc_c = $s->rfc_c;
								$incidencia->nombre_c = $s->nombre_c;
								$incidencia->rfc_e = $s->rfc_e;
								$incidencia->nombre_e = $s->nombre_e;
								$incidencia->id_cliente = $s->id_cliente;
								$incidencia->timbrado = "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1";
								$incidencia->id_pro = $procesoNuevo;
								$incidencia->id_es = $s->id_es;
								$incidencia->save();

								DB::table("pago")
								->where('id_pago', '=', $s->id_pago)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 1"]);

								DB::table("similaridades")
								->delete();
					    	}
					    }
					    else{
					    	$similares = DB::table("similaridades")
					    	->get();

					    	$sol = DB::table("similaridades")->delete();

					    	foreach ($similares as $si) {
					    		if(strlen($si->FECHAPAG) == 23){
						    		$separacion = explode(" ", $si->FECHAPAG);
							    	$date = explode("-", $separacion[0]);
							    	$date = $date[2]."".$date[1]."".$date[0];
							    	$fecha_T = $date;
						    	}
						    	else{
						    		$fecha_T =  $si->FECHAPAG;
						    	}
						    	$fecha_S = str_replace(".", "", $s->fechadoc);

					    		if($fecha_S == $fecha_T){//Coincide la moneda. Se va a similaridades.
				    				$simi = new Similaridades;
				    				$simi->id_tt = $si->id_tt;
								    $simi->RFC_R = $si->RFC_R;
									$simi->MONTOP = $si->MONTOP;
									$simi->MONEDAP = $si->MONEDAP;
									$simi->NUMEROPERP = $si->NUMEROPERP;
									$simi->RFCCTABEN = $si->RFCCTABEN;
									$simi->CATABEN = $si->CATABEN;
									$simi->FORMAP = $si->FORMAP;
									$simi->RFCCTAORD = $si->RFCCTAORD;
									$simi->BANCOORDEXT = $si->BANCOORDEXT;
									$simi->CTAORD = $si->CTAORD;
									$simi->FECHAPAG = $si->FECHAPAG;
									$simi->save();
					    		}
					    	}

					    	$conteo = DB::table("similaridades")
						    ->count();

						    $simil = DB::table("similaridades")
						    ->get();

						    if($conteo == 0){
						    	$incidencia = new Incidencias;
						    	$incidencia->id_pago = $s->id_pago;
							    $incidencia->clearing_document = $s->clearing_document;
								$incidencia->version = $s->version;
								$incidencia->fecha_clearing = $s->fecha_clearing;
								$incidencia->regimen = $s->regimen;
								$incidencia->lugarexpedicion = $s->lugarexpedicion;
								$incidencia->residenciafiscal = $s->residenciafiscal;
								$incidencia->numregidtrib = $s->numregidtrib;
								$incidencia->confirmacion = $s->confirmacion;
								$incidencia->formap = $s->formap;
								$incidencia->monedaP = $s->monedaP;
								$incidencia->fechap = $s->fechap;
								$incidencia->tipocambioP = $s->tipocambioP;
								$incidencia->montoP = $s->montoP;
								$incidencia->signo = $s->signo;
								$incidencia->numeroperP = $s->numeroperP;
								$incidencia->rfcctaord = $s->rfcctaord;
								$incidencia->bancoordext = $s->bancoordext;
								$incidencia->ctaord = $s->ctaord;
								$incidencia->rfcctaben = $s->rfcctaben;
								$incidencia->cataben = $s->cataben;
								$incidencia->rfc_c = $s->rfc_c;
								$incidencia->nombre_c = $s->nombre_c;
								$incidencia->rfc_e = $s->rfc_e;
								$incidencia->nombre_e = $s->nombre_e;
								$incidencia->id_cliente = $s->id_cliente;
								$incidencia->timbrado = "No existe relaci??n de fechas de pago entre tesoreria y SAP";
								$incidencia->id_pro = $procesoNuevo;
								$incidencia->id_es = $s->id_es;
								$incidencia->save();

								DB::table("pago")
								->where('clearing_document', '=', $s->clearing_document)
								->whereRaw(DB::raw('('.$busquedaSAP.')'))
								->update(["timbrado" => "No existe relaci??n de fechas de pago entre tesoreria y SAP"]);
						    }
						    elseif($conteo == 1){
						    	$cfdirels = DB::table("parcialidades")
						    	->where('clearing_document', '=', $s->clearing_document)
						    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    	->count();

						    	if($cfdirels > 0){
						    		$cfdirels = DB::table("parcialidades")
						    		->where('clearing_document', '=', $s->clearing_document)
						    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    		->get();

						    		$repetidos = false;

						    		foreach ($cfdirels as $cfdi) {
						    			$solo_uno = DB::table('parcialidades')
						    			->where('folio', '=', $cfdi->folio)
						    			->where('clearing_document', '=', $cfdi->clearing_document)
						    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    			->count();

						    			if($solo_uno > 1){
						    				$solo_uno = DB::table('parcialidades')
						    				->where('folio', '=', $cfdi->folio)
						    				->where('imppagado', '=', $cfdi->imppagado)
						    				->where('clearing_document', '=', $cfdi->clearing_document)
						    				->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    				->delete();

						    				$solo_uno = DB::table('parcialidades')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    			->count();

						    				if($solo_uno > 1){
						    					$repetidos = true;
						    				}
						    			}
						    		}

						    		if($repetidos == false){
						    			$saldadoParcialidades = false;
						    			$numSalParc = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosP = DB::table("parcialidades")
								    		->where('clearing_document', '<', $s->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosP > 0){
								    			$ultimoParcial = DB::table("parcialidades")
									    		->where('clearing_document', '<', $s->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_par", "desc")
									    		->first();
									    		if($ultimoParcial->impsaldoins == 0){
									    			$saldadoParcialidades= true;
									    			$numSalParc .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			

					    				if($saldadoParcialidades == false){
					    					$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('id_pago', '=', $s->id_pago)
											->update(["timbrado" => "1"]);

											DB::table("tesoreria")
											->where('id_tt', '=', $simil[0]->id_tt)
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
					    				}
					    				else{
					    					$complemento = new Complemento;
								    		$complemento->id_pago = $s->id_pago;
										    $complemento->clearing_document = $s->clearing_document;
											$complemento->version = $s->version;
											$complemento->fecha_clearing = $s->fecha_clearing;
											$complemento->regimen = $s->regimen;
											$complemento->lugarexpedicion = $s->lugarexpedicion;
											$complemento->residenciafiscal = $s->residenciafiscal;
											$complemento->numregidtrib = $s->numregidtrib;
											$complemento->confirmacion = $s->confirmacion;
											$complemento->formap = $simil[0]->FORMAP;
											$complemento->monedaP = $s->monedaP;
											$complemento->fechap = $simil[0]->FECHAPAG;
											$complemento->fechabus = $s->fechabus;
											$complemento->tipocambioP = $s->tipocambioP;
											$complemento->montoP = $s->montoP;
											$complemento->signo = $s->signo;
											$complemento->numeroperP = $simil[0]->NUMEROPERP;
											$complemento->rfcctaord = $simil[0]->RFCCTAORD;
											$complemento->bancoordext = $simil[0]->BANCOORDEXT;
											$complemento->ctaord = $simil[0]->CTAORD;
											$complemento->rfcctaben = $simil[0]->RFCCTABEN;
											if($simil[0]->FORMAP == "03" || $simil[0]->FORMAP == "3" || $simil[0]->FORMAP == 3){
												if($simil[0]->CATABEN == "82500849779" || $simil[0]->CATABEN == 82500849779){
													$complemento->cataben = "014180825008497793";
												}
												elseif($simil[0]->CATABEN == "65505095178" || $simil[0]->CATABEN == 65505095178){
													$complemento->cataben = "014180655050951787";
												}
												else{
													$complemento->cataben = $simil[0]->CATABEN;
												}
											}
											else{
												$complemento->cataben = $simil[0]->CATABEN;
											}
											$complemento->rfc_c = $s->rfc_c;
											$complemento->nombre_c = $s->nombre_c;
											$complemento->rfc_e = $s->rfc_e;
											$complemento->nombre_e = $s->nombre_e;
											$complemento->id_cliente = $s->id_cliente;
											$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('clearing_document', '=', $s->clearing_document)
											->whereRaw(DB::raw('('.$busquedaSAP.')'))
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
					    				}
						    		}
						    		else{
						    			$incidencia = new Incidencias;
							    		$incidencia->id_pago = $s->id_pago;
									    $incidencia->clearing_document = $s->clearing_document;
										$incidencia->version = $s->version;
										$incidencia->fecha_clearing = $s->fecha_clearing;
										$incidencia->regimen = $s->regimen;
										$incidencia->lugarexpedicion = $s->lugarexpedicion;
										$incidencia->residenciafiscal = $s->residenciafiscal;
										$incidencia->numregidtrib = $s->numregidtrib;
										$incidencia->confirmacion = $s->confirmacion;
										$incidencia->formap = $s->formap;
										$incidencia->monedaP = $s->monedaP;
										$incidencia->fechap = $s->fechap;
										$incidencia->tipocambioP = $s->tipocambioP;
										$incidencia->montoP = $s->montoP;
										$incidencia->signo = $s->signo;
										$incidencia->numeroperP = $s->numeroperP;
										$incidencia->rfcctaord = $s->rfcctaord;
										$incidencia->bancoordext = $s->bancoordext;
										$incidencia->ctaord = $s->ctaord;
										$incidencia->rfcctaben = $s->rfcctaben;
										$incidencia->cataben = $s->cataben;
										$incidencia->rfc_c = $s->rfc_c;
										$incidencia->nombre_c = $s->nombre_c;
										$incidencia->rfc_e = $s->rfc_e;
										$incidencia->nombre_e = $s->nombre_e;
										$incidencia->timbrado = "Se ha registrado un folio mas de una vez, en este pago.";
										$incidencia->id_pro = $procesoNuevo;
										$incidencia->id_es = $s->id_es;
										$incidencia->save();

										DB::table("pago")
										->where('id_pago', '=', $s->id_pago)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("similaridades")
										->delete();
						    		}
						    	}

						    	else{
						    		$incidencia = new Incidencias;
						    		$incidencia->id_pago = $s->id_pago;
								    $incidencia->clearing_document = $s->clearing_document;
									$incidencia->version = $s->version;
									$incidencia->fecha_clearing = $s->fecha_clearing;
									$incidencia->regimen = $s->regimen;
									$incidencia->lugarexpedicion = $s->lugarexpedicion;
									$incidencia->residenciafiscal = $s->residenciafiscal;
									$incidencia->numregidtrib = $s->numregidtrib;
									$incidencia->confirmacion = $s->confirmacion;
									$incidencia->formap = $s->formap;
									$incidencia->monedaP = $s->monedaP;
									$incidencia->fechap = $s->fechap;
									$incidencia->tipocambioP = $s->tipocambioP;
									$incidencia->montoP = $s->montoP;
									$incidencia->signo = $s->signo;
									$incidencia->numeroperP = $s->numeroperP;
									$incidencia->rfcctaord = $s->rfcctaord;
									$incidencia->bancoordext = $s->bancoordext;
									$incidencia->ctaord = $s->ctaord;
									$incidencia->rfcctaben = $s->rfcctaben;
									$incidencia->cataben = $s->cataben;
									$incidencia->rfc_c = $s->rfc_c;
									$incidencia->nombre_c = $s->nombre_c;
									$incidencia->rfc_e = $s->rfc_e;
									$incidencia->nombre_e = $s->nombre_e;
									$incidencia->id_cliente = $s->id_cliente;
									$incidencia->timbrado = "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 2";
									$incidencia->id_pro = $procesoNuevo;
									$incidencia->id_es = $s->id_es;
									$incidencia->save();

									DB::table("pago")
									->where('id_pago', '=', $s->id_pago)
									->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 2"]);

									DB::table("similaridades")
									->delete();
						    	}
						    }
						    else{
						    	$incidencia = new Incidencias;
						    	$incidencia->id_pago = $s->id_pago;
							    $incidencia->clearing_document = $s->clearing_document;
								$incidencia->version = $s->version;
								$incidencia->fecha_clearing = $s->fecha_clearing;
								$incidencia->regimen = $s->regimen;
								$incidencia->lugarexpedicion = $s->lugarexpedicion;
								$incidencia->residenciafiscal = $s->residenciafiscal;
								$incidencia->numregidtrib = $s->numregidtrib;
								$incidencia->confirmacion = $s->confirmacion;
								$incidencia->formap = $s->formap;
								$incidencia->monedaP = $s->monedaP;
								$incidencia->fechap = $s->fechap;
								$incidencia->tipocambioP = $s->tipocambioP;
								$incidencia->montoP = $s->montoP;
								$incidencia->signo = $s->signo;
								$incidencia->numeroperP = $s->numeroperP;
								$incidencia->rfcctaord = $s->rfcctaord;
								$incidencia->bancoordext = $s->bancoordext;
								$incidencia->ctaord = $s->ctaord;
								$incidencia->rfcctaben = $s->rfcctaben;
								$incidencia->cataben = $s->cataben;
								$incidencia->rfc_c = $s->rfc_c;
								$incidencia->nombre_c = $s->nombre_c;
								$incidencia->rfc_e = $s->rfc_e;
								$incidencia->nombre_e = $s->nombre_e;
								$incidencia->id_cliente = $s->id_cliente;
								$incidencia->timbrado = "Hay ambig??edad entre tesorer??a y SAP. Multiples datos de tesorer??a se asocian con este pago de SAP.";
								$incidencia->id_pro = $procesoNuevo;
								$incidencia->id_es = $s->id_es;
								$incidencia->save();

								DB::table("pago")
								->where('id_pago', '=', $s->id_pago)
								->update(["timbrado" => "Hay ambig??edad entre tesorer??a y SAP. Multiples datos de tesorer??a se asocian con este pago de SAP."]);

								DB::table("similaridades")
									->truncate();
						    }
					    }
		    		}
		    		else{
		    			$incidencia = new Incidencias;
				    	$incidencia->id_pago = $s->id_pago;
						$incidencia->clearing_document = $s->clearing_document;
						$incidencia->version = $s->version;
						$incidencia->fecha_clearing = $s->fecha_clearing;
						$incidencia->regimen = $s->regimen;
						$incidencia->lugarexpedicion = $s->lugarexpedicion;
						$incidencia->residenciafiscal = $s->residenciafiscal;
						$incidencia->numregidtrib = $s->numregidtrib;
						$incidencia->confirmacion = $s->confirmacion;
						$incidencia->formap = $s->formap;
						$incidencia->monedaP = $s->monedaP;
						$incidencia->fechap = $s->fechap;
						$incidencia->tipocambioP = $s->tipocambioP;
						$incidencia->montoP = $s->montoP;
						$incidencia->signo = $s->signo;
						$incidencia->numeroperP = $s->numeroperP;
						$incidencia->rfcctaord = $s->rfcctaord;
						$incidencia->bancoordext = $s->bancoordext;
						$incidencia->ctaord = $s->ctaord;
						$incidencia->rfcctaben = $s->rfcctaben;
						$incidencia->cataben = $s->cataben;
						$incidencia->rfc_c = $s->rfc_c;
						$incidencia->nombre_c = $s->nombre_c;
						$incidencia->rfc_e = $s->rfc_e;
						$incidencia->nombre_e = $s->nombre_e;
						$incidencia->id_cliente = $s->id_cliente;
						$incidencia->timbrado = "Los documentos referenciados de este pago no tienen folio.";
						$incidencia->id_pro = $procesoNuevo;
						$incidencia->id_es = $s->id_es;
						$incidencia->save();

						DB::table("pago")
						->where('id_pago', '=', $s->id_pago)
						->update(["timbrado" => "Los documentos referenciados de este pago no tienen folio."]);
		    		}
		    	}
		    	else{
		    		//No existe RFC o nombre de cliente en pago de tesorer??a, se va a incidencia
			    	$incidencia = new Incidencias;
			    	$incidencia->id_pago = $s->id_pago;
					$incidencia->clearing_document = $s->clearing_document;
					$incidencia->version = $s->version;
					$incidencia->fecha_clearing = $s->fecha_clearing;
					$incidencia->regimen = $s->regimen;
					$incidencia->lugarexpedicion = $s->lugarexpedicion;
					$incidencia->residenciafiscal = $s->residenciafiscal;
					$incidencia->numregidtrib = $s->numregidtrib;
					$incidencia->confirmacion = $s->confirmacion;
					$incidencia->formap = $s->formap;
					$incidencia->monedaP = $s->monedaP;
					$incidencia->fechap = $s->fechap;
					$incidencia->tipocambioP = $s->tipocambioP;
					$incidencia->montoP = $s->montoP;
					$incidencia->signo = $s->signo;
					$incidencia->numeroperP = $s->numeroperP;
					$incidencia->rfcctaord = $s->rfcctaord;
					$incidencia->bancoordext = $s->bancoordext;
					$incidencia->ctaord = $s->ctaord;
					$incidencia->rfcctaben = $s->rfcctaben;
					$incidencia->cataben = $s->cataben;
					$incidencia->rfc_c = $s->rfc_c;
					$incidencia->nombre_c = $s->nombre_c;
					$incidencia->rfc_e = $s->rfc_e;
					$incidencia->nombre_e = $s->nombre_e;
					$incidencia->id_cliente = $s->id_cliente;
					$incidencia->timbrado = "El cliente no fue especificado en el pago o no existe.";
					$incidencia->id_pro = $procesoNuevo;
					$incidencia->id_es = $s->id_es;
					$incidencia->save();

					DB::table("pago")
					->where('id_pago', '=', $s->id_pago)
					->update(["timbrado" => "El cliente no fue especificado en el pago o no existe."]);
		    	}
		    }

		    $refer = DB::table("pago")
		    ->where("timbrado", "=", "No existe relaci??n de montos y monedas entre tesoreria y SAP")
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->get();

		    $bancosSAP = DB::table("bancos_SAP")
		    ->get();

		    foreach ($refer as $r) {
		    	$ex = false;
		    	$monto = "";
		    	foreach ($bancosSAP as $b) {
		    		if($r->clearing_document == $b->clearing_document){
		    			$ex = true;
		    			if($r->monedaP != "MXN"){
		    				$monto = $b->monto;
		    			}
		    			else{
		    				$monto = $b->montomxn;
		    			}
		    			break;
		    		}
		    	}

		    	$banco = explode(" ", $r->reference);
		    	if($banco[0] == "JPMUSUSD"){

		    		$cfdirels = DB::table("parcialidades")
					->where('clearing_document', '=', $r->clearing_document)
					->whereRaw(DB::raw('('.$busquedaSAP.')'))
					->count();

			    	if($cfdirels > 0){
					    		$cfdirels = DB::table("parcialidades")
					    		->where('clearing_document', '=', $r->clearing_document)
					    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
					    		->get();

					    		$repetidos = false;

					    		foreach ($cfdirels as $cfdi) {
					    			$solo_uno = DB::table('parcialidades')
						    			->where('folio', '=', $cfdi->folio)
						    			->where('clearing_document', '=', $cfdi->clearing_document)
						    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    			->count();

						    			if($solo_uno > 1){
						    				$solo_uno = DB::table('parcialidades')
						    				->where('folio', '=', $cfdi->folio)
						    				->where('imppagado', '=', $cfdi->imppagado)
						    				->where('clearing_document', '=', $cfdi->clearing_document)
						    				->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    				->delete();

						    				$solo_uno = DB::table('parcialidades')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    			->count();

						    				if($solo_uno > 1){
						    					$repetidos = true;
						    				}
						    			}
					    		}

					    		if($repetidos == false){
					    			$saldadoParcialidades = false;
						    			$numSalParc = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosP = DB::table("parcialidades")
								    		->where('clearing_document', '<', $r->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosP > 0){
								    			$ultimoParcial = DB::table("parcialidades")
									    		->where('clearing_document', '<', $r->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_par", "desc")
									    		->first();
									    		if($ultimoParcial->impsaldoins == 0){
									    			$saldadoParcialidades= true;
									    			$numSalParc .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			

					    				if($saldadoParcialidades == false){
					    					$fechaar = str_replace(".", "", $r->fechadoc);
								    		$fechaar = $fechaar."120000";
								    		DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "03",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		$complemento = new Complemento;
							    			$complemento->id_pago = $r->id_pago;
										    $complemento->clearing_document = $r->clearing_document;
											$complemento->version = $r->version;
											$complemento->fecha_clearing = $r->fecha_clearing;
											$complemento->regimen = $r->regimen;
											$complemento->lugarexpedicion = $r->lugarexpedicion;
											$complemento->residenciafiscal = $r->residenciafiscal;
											$complemento->numregidtrib = $r->numregidtrib;
											$complemento->confirmacion = $r->confirmacion;
											$complemento->formap = "03";
											$complemento->monedaP = $r->monedaP;
											$complemento->fechap = $fechaar;
											$complemento->fechabus = $r->fechabus;
											$complemento->tipocambioP = $r->tipocambioP;
											$complemento->montoP = $r->montoP;
											$complemento->signo = $r->signo;
											$complemento->numeroperP = "";
											$complemento->rfcctaord = "";
											$complemento->bancoordext = "";
											$complemento->ctaord = "";
											$complemento->rfcctaben = "";
											$complemento->cataben = "0700626190";
											$complemento->rfc_c = $r->rfc_c;
											$complemento->nombre_c = $r->nombre_c;
											$complemento->rfc_e = $r->rfc_e;
											$complemento->nombre_e = $r->nombre_e;
											$complemento->id_cliente = $r->id_cliente;
											$complemento->timbrado = "1";
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->delete();
					    				}
					    				else{
					    					$complemento = new Complemento;
							    			$complemento->id_pago = $r->id_pago;
										    $complemento->clearing_document = $r->clearing_document;
											$complemento->version = $r->version;
											$complemento->fecha_clearing = $r->fecha_clearing;
											$complemento->regimen = $r->regimen;
											$complemento->lugarexpedicion = $r->lugarexpedicion;
											$complemento->residenciafiscal = $r->residenciafiscal;
											$complemento->numregidtrib = $r->numregidtrib;
											$complemento->confirmacion = $r->confirmacion;
											$complemento->formap = "03";
											$complemento->monedaP = $r->monedaP;
											$complemento->fechap = $fechaar;
											$complemento->fechabus = $r->fechabus;
											$complemento->tipocambioP = $r->tipocambioP;
											$complemento->montoP = $r->montoP;
											$complemento->signo = $r->signo;
											$complemento->numeroperP = "";
											$complemento->rfcctaord = "";
											$complemento->bancoordext = "";
											$complemento->ctaord = "";
											$complemento->rfcctaben = "";
											$complemento->cataben = "0700626190";
											$complemento->rfc_c = $r->rfc_c;
											$complemento->nombre_c = $r->nombre_c;
											$complemento->rfc_e = $r->rfc_e;
											$complemento->nombre_e = $r->nombre_e;
											$complemento->id_cliente = $r->id_cliente;
											$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
											$complemento->id_pro = $procesoNuevo;
											$complemento->id_es = $s->id_es;
											$complemento->save();

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "1"]);

											DB::table("similaridades")
											->delete();
					    				}
					    		}
					    		else{
					    			DB::table("incidencias")
									->where('id_pago', '=', $r->id_pago)
									->where('id_pro', '=', $procesoNuevo)
									->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

									DB::table("pago")
									->where('id_pago', '=', $r->id_pago)
									->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

									DB::table("similaridades")
									->delete();
					    		}
			    	}
			    	else{
			    		DB::table("incidencias")
								->where('id_pago', '=', $r->id_pago)
								->where('id_pro', '=', $procesoNuevo)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("pago")
								->where('id_pago', '=', $r->id_pago)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("similaridades")
								->delete();
			    	}


		    	}
		    	else{
		    		if($r->nombre_c == "ARVATO DE MEXICO, S.A. DE C.V." || $r->nombre_c == "QUIMICA ONTARIO, S.A. DE C.V." || $r->nombre_c == "CALZADO CHAVITA,  S.A. DE C.V." || $r->nombre_c == "INDUSTRIAS SYLPYL, S.A. DE C.V." || $r->nombre_c == "INDUSTRIAL DE PINTURAS ECATEPEC, S.A. DE C.V." || $r->nombre_c == "DURAN CHEMICALS, S.A. DE C.V." || $r->nombre_c == "COMEX INDUSTRIAL COATINGS, S.A. DE C.V." || $r->nombre_c == "FABRICA DE PINTURAS UNIVERSALES, S.A. DE C.V."|| $r->nombre_c == "FXI DE CUAUTITLAN, S.A. DE C.V." || $r->nombre_c == "PROVEEDURIA INTERNACIONAL DE LEON, S.A. DE C.V."|| $r->nombre_c == "PRODUCTOS RIVIAL, S.A. DE C.V."|| $r->nombre_c == "Manufacturera de Calzado PMA S.A. d" ){
		    			$cfdirels = DB::table("parcialidades")
						->where('clearing_document', '=', $r->clearing_document)
						->count();

				    	if($cfdirels > 0){
						    		$cfdirels = DB::table("parcialidades")
						    		->where('clearing_document', '=', $r->clearing_document)
						    		->whereRaw(DB::raw('('.$busquedaSAP.')'))
						    		->get();

						    		$repetidos = false;

						    		foreach ($cfdirels as $cfdi) {
						    			$solo_uno = DB::table('parcialidades')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    			->count();

							    			if($solo_uno > 1){
							    				$solo_uno = DB::table('parcialidades')
							    				->where('folio', '=', $cfdi->folio)
							    				->where('imppagado', '=', $cfdi->imppagado)
							    				->where('clearing_document', '=', $cfdi->clearing_document)
							    				->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    				->delete();

							    				$solo_uno = DB::table('parcialidades')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    			->count();

							    				if($solo_uno > 1){
							    					$repetidos = true;
							    				}
							    			}
						    		}

						    		if($repetidos == false){
						    			$saldadoParcialidades = false;
						    			$numSalParc = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosP = DB::table("parcialidades")
								    		->where('clearing_document', '<', $r->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosP > 0){
								    			$ultimoParcial = DB::table("parcialidades")
									    		->where('clearing_document', '<', $r->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_par", "desc")
									    		->first();
									    		if($ultimoParcial->impsaldoins == 0){
									    			$saldadoParcialidades= true;
									    			$numSalParc .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			

					    				if($saldadoParcialidades == false){	
							    			$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "02",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "82500849779";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "65505095178";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

											else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
											}
						    			}
						    			else{
						    				$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "02",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "82500849779";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "65505095178";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

											else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
											}
						    				DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "1"]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalParc]);

											DB::table("similaridades")
											->delete();
						    			}
						    		}
						    		else{
						    			DB::table("incidencias")
										->where('id_pago', '=', $r->id_pago)
										->where('id_pro', '=', $procesoNuevo)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("pago")
										->where('id_pago', '=', $r->id_pago)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("similaridades")
										->delete();
						    		}
				    	}
				    	else{
			    			DB::table("incidencias")
								->where('id_pago', '=', $r->id_pago)
								->where('id_pro', '=', $procesoNuevo)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("pago")
								->where('id_pago', '=', $r->id_pago)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 4"]);

								DB::table("similaridades")
								->delete();
			    		}
		    		}
		    		else{
		    			$cfdirels = DB::table("parcialidades")
						    	->where('clearing_document', '=', $r->clearing_document)
						    	->count();
		    					if($cfdirels > 0){
						    		$cfdirels = DB::table("parcialidades")
						    		->where('clearing_document', '=', $r->clearing_document)
						    		->get();

						    		$repetidos = false;

						    		foreach ($cfdirels as $cfdi) {
						    			$solo_uno = DB::table('parcialidades')
							    			->where('folio', '=', $cfdi->folio)
							    			->where('clearing_document', '=', $cfdi->clearing_document)
							    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    			->count();

							    			if($solo_uno > 1){
							    				$solo_uno = DB::table('parcialidades')
							    				->where('folio', '=', $cfdi->folio)
							    				->where('imppagado', '=', $cfdi->imppagado)
							    				->where('clearing_document', '=', $cfdi->clearing_document)
							    				->whereRaw(DB::raw('('.$busquedaSAP.')'))
							    				->delete();

							    				$solo_uno = DB::table('parcialidades')
								    			->where('folio', '=', $cfdi->folio)
								    			->where('clearing_document', '=', $cfdi->clearing_document)
								    			->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    			->count();

							    				if($solo_uno > 1){
							    					$repetidos = true;
							    				}
							    			}
						    		}

						    		if($repetidos == false){
						    			$saldadoParcialidades = false;
						    			$numSalParc = "";
						    			foreach ($cfdirels as $cfdi) {
						    				$ultimosP = DB::table("parcialidades")
								    		->where('clearing_document', '<', $r->clearing_document)
								    		->where('folio', '=', $cfdi->folio)
								    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
								    		->count();
								    		if($ultimosP > 0){
								    			$ultimoParcial = DB::table("parcialidades")
									    		->where('clearing_document', '<', $r->clearing_document)
									    		->where('folio', '=', $cfdi->folio)
									    		//->whereRaw(DB::raw('('.$busquedaSAP.')'))
									    		->orderBy("id_par", "desc")
									    		->first();
									    		if($ultimoParcial->impsaldoins == 0){
									    			$saldadoParcialidades= true;
									    			$numSalParc .= $cfdi->folio.",";
									    		}
								    		}
						    			}
						    			

					    				if($saldadoParcialidades == false){
						    				$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "03",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "014180825008497793";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "03";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "014180655050951787";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "1";
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
											}
						    			}
						    			else{
						    				$fechaar = str_replace(".", "", $r->fechadoc);
							    			$fechaar = $fechaar."120000";
							    			DB::table("pago")
								    		->where("id_pago", "=", $r->id_pago)
								    		->update([
								    			"formap" => "02",
								    			"fechap" => $fechaar,
								    			"timbrado" => "Se obtuvieron los datos de SAP",
								    		]);

								    		if($banco[0] == "BBVA" && $banco[1] == "USD"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199889965";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

								    		elseif($banco[0] == "BBVA" && $banco[1] == "MXN"){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BBA830831LJ2";
													$complemento->cataben = "0199073027";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "USD") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "USD")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "82500849779";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}
								    		elseif(($banco[0] == "SANT" && $banco[1] == "MXN") || (substr($banco[0], 0, 4) == "SANT" && substr($banco[0], 5, 8) == "MXN")){
								    			$complemento = new Complemento;
								    			$complemento->id_pago = $r->id_pago;
												    $complemento->clearing_document = $r->clearing_document;
													$complemento->version = $r->version;
													$complemento->fecha_clearing = $r->fecha_clearing;
													$complemento->regimen = $r->regimen;
													$complemento->lugarexpedicion = $r->lugarexpedicion;
													$complemento->residenciafiscal = $r->residenciafiscal;
													$complemento->numregidtrib = $r->numregidtrib;
													$complemento->confirmacion = $r->confirmacion;
													$complemento->formap = "02";
													$complemento->monedaP = $r->monedaP;
													$complemento->fechap = $fechaar;
													$complemento->fechabus = $r->fechabus;
													$complemento->tipocambioP = $r->tipocambioP;
													$complemento->montoP = $r->montoP;
													$complemento->signo = $r->signo;
													$complemento->numeroperP = "";
													$complemento->rfcctaord = "";
													$complemento->bancoordext = "";
													$complemento->ctaord = "";
													$complemento->rfcctaben = "BSM970519DU8";
													$complemento->cataben = "65505095178";
													$complemento->rfc_c = $r->rfc_c;
													$complemento->nombre_c = $r->nombre_c;
													$complemento->rfc_e = $r->rfc_e;
													$complemento->nombre_e = $r->nombre_e;
													$complemento->id_cliente = $r->id_cliente;
													$complemento->timbrado = "Las siguientes facturas ya fueron liquidadas: ".$numSalParc;
													$complemento->id_pro = $procesoNuevo;
													$complemento->id_es = $s->id_es;
													$complemento->save();

													DB::table("incidencias")
													->where('id_pago', '=', $r->id_pago)
													->where('id_pro', '=', $procesoNuevo)
													->delete();
								    		}

											else{
												DB::table("incidencias")
												->where('id_pago', '=', $r->id_pago)
												->where('id_pro', '=', $procesoNuevo)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);

												DB::table("pago")
												->where('id_pago', '=', $r->id_pago)
												->update(["timbrado" => "No hay relaci??n entre tesorer??a y SAP"]);
											}
						    				DB::table("incidencias")
											->where('id_pago', '=', $r->id_pago)
											->where('id_pro', '=', $procesoNuevo)
											->update(["timbrado" => "1"]);

											DB::table("pago")
											->where('id_pago', '=', $r->id_pago)
											->update(["timbrado" => "Las siguientes facturas ya fueron liquidadas: ".$numSalParc]);

											DB::table("similaridades")
											->delete();
						    			}
						    		}
						    		else{
						    			DB::table("incidencias")
										->where('id_pago', '=', $r->id_pago)
										->where('id_pro', '=', $procesoNuevo)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("pago")
										->where('id_pago', '=', $r->id_pago)
										->update(["timbrado" => "Se ha registrado un folio mas de una vez, en este pago."]);

										DB::table("similaridades")
										->delete();
						    		}
				    	}
				    	else{
			    			DB::table("incidencias")
								->where('id_pago', '=', $r->id_pago)
								->where('id_pro', '=', $procesoNuevo)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("pago")
								->where('id_pago', '=', $r->id_pago)
								->update(["timbrado" => "Este pago no cuenta con facturas ni notas de cr??dito relacionadas. 3"]);

								DB::table("similaridades")
								->delete();
			    		}
		    		}
		    	}
		    }

		    $arrow = DB::table("pago")
		    ->select('clearing_document')
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->get();

		    $array[0] = "";
		    $a = 0;

		    foreach ($arrow as $c7) {
		    	$array[$a] = $c7->clearing_document;
		    	$a++;
		    }

		    $existe = DB::table('parcialidades')
		    ->select('clearing_document', 'id_es')
		    ->whereNotIn("clearing_document", $array)
		    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    ->groupBy('clearing_document', 'id_es')
		    ->get();

		    foreach ($existe as $e) {
		    	$totalidad = DB::table("parcialidades")
		    	->where("clearing_document", "=", $e->clearing_document)
		    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    	->get();

		    	$total_pag = 0;

		    	foreach ($totalidad as $total) {
		    		$total_pag = $total_pag + (float)$total->imppagado;
		    	}

		    	$pari = DB::table("parcialidades")
		    	->where("clearing_document", "=", $e->clearing_document)
		    	->whereRaw(DB::raw('('.$busquedaSAP.')'))
		    	->first();

		    	$pago = DB::table('pago')->insertGetId([
		    		"clearing_document" => $e->clearing_document,
						"version" => "1",
						"fecha_clearing" => "",
						"regimen" => "601",
						"lugarexpedicion" => "",
						"residenciafiscal" => "",
						"numregidtrib" => "",
						"confirmacion" => "",
						"formap" => "25",
						"monedaP" => $pari->moneda,
						"fechap" => "",
						"fechadoc" => "",
						"assignment" => "",
						"reference" => "",
						"tipocambioP" => $pari->tipcambio,
						"signo" => "+",
						"montoP" => $total_pag,
						"numeroperP" => "",
						"rfcctaord" => "",
						"bancoordext" => "",
						"ctaord" => "",
						"cataben" => "",
						"rfc_c" => $pari->rfc_c,
						"nombre_c" => $pari->nombre_c,
						"id_cliente" => $pari->id_cliente,
						"rfc_e" => "",
						"nombre_e" => "",
						"timbrado" => "Es pago con clearing 701",
						"id_es" => $e->id_es,
		    	]);

		    	$r = DB::table("pago")
		    	->where("id_pago", "=", $pago)
		    	->first();

						$complemento = new Complemento;
							    			$complemento->id_pago = $r->id_pago;
											    $complemento->clearing_document = $r->clearing_document;
												$complemento->version = $r->version;
												$complemento->fecha_clearing = $r->fecha_clearing;
												$complemento->regimen = $r->regimen;
												$complemento->lugarexpedicion = $r->lugarexpedicion;
												$complemento->residenciafiscal = $r->residenciafiscal;
												$complemento->numregidtrib = $r->numregidtrib;
												$complemento->confirmacion = $r->confirmacion;
												$complemento->formap = $r->formap;
												$complemento->monedaP = $r->monedaP;
												$complemento->fechap = $fechaar;
												$complemento->tipocambioP = $r->tipocambioP;
												$complemento->montoP = $r->montoP;
												$complemento->signo = $r->signo;
												$complemento->numeroperP = "";
												$complemento->rfcctaord = "";
												$complemento->bancoordext = "";
												$complemento->ctaord = "";
												$complemento->rfcctaben = "";
												$complemento->cataben = "";
												$complemento->rfc_c = $r->rfc_c;
												$complemento->nombre_c = $r->nombre_c;
												$complemento->rfc_e = $r->rfc_e;
												$complemento->nombre_e = $r->nombre_e;
												$complemento->timbrado = "1";
												$complemento->id_pro = $procesoNuevo;
												$complemento->save();
		    }
		}

	    DB::table('excel_SAP')
	    ->whereRaw(DB::raw('('.$busquedaSAP.')'))
	    ->update([
	    	"id_pro" => $procesoNuevo,
	    	"integrado" => 1,
	    ]);

	    DB::table('excel_tesoreria')
	    ->whereRaw(DB::raw('('.$busquedaTeso.')'))
	    ->update([
	    	"id_pro" => $procesoNuevo,
	    	"integrado" => 1,
	    ]);

	    return response()->json([
	    	"respuesta" => 1
	    ]);

    }

    public function verIntegrados()
    {
    	$procesos = DB::table('procesos')
    	->count();

    	$proceso = DB::table('procesos')
    	->max('id_pro');

    	$procesoU = DB::table('procesos')
    	->where('id_pro', '=', $proceso)
    	->first();

    	$tesoinci = DB::table("tesoreria")
	    ->where("timbrado", '=', '0')
	    ->get();

	    $tesocor = DB::table("tesoreria")
	    ->where("timbrado", '=', '1')
	    ->get();

	    $clearings = DB::table("complemento")
	    ->select("clearing_document")
	    ->get();

	    /*$facnoencontradas = DB::table("factura as f")
	    ->join("parcialidades as p", "f.folio", "=", "p.folio")
	    ->whereNotIn("f.clearing_document", $clearings)
	    ->get();*/

	    $correctos = DB::table("complemento")
	    ->where("id_pro", '=', $proceso)
	    ->get();

	    $cuantosCorrectos = DB::table("complemento")
	    ->where("id_pro", '=', $proceso)
	    ->count();

	    $incidentes = DB::table("incidencias")
	    ->where("id_pro", '=', $proceso)
	    ->get();

	    $cuantosIncidentes = DB::table("incidencias")
	    ->where('id_pro', '=', $proceso)
	    ->count();

	    $in = DB::table("incidencias")
	    ->where("id_pro", '=', $proceso)
	    ->count();

	    $facturas = DB::table("factura")
	    ->get();

	    $parcialidades = DB::table("parcialidades")
	    ->get();

	    return view("complementoPagos", ["tesoinci" =>$tesoinci, "tesocor" => $tesocor, "correctos" => $correctos, "incidentes" => $incidentes, "facturas" => $facturas, "parcialidades" => $parcialidades, "in" => $in, "proceso" => $procesoU, "cuantosCorrectos" => $cuantosCorrectos, "cuantosIncidentes" => $cuantosIncidentes, "procesos" => $procesos]);
    }

    public function incidencias(Request $request)
    {
    	$textoSAP = "";
    	$textoTeso = "";
    	$pag = 0;

    	$proceso = DB::table("procesos")
		->max("id_pro");

    	$tesoreria = DB::table('excel_tesoreria as et')
    	->join('tesoreria as t', 'et.id_et', '=', 't.id_et')
    	->where('et.id_pro', '=', $proceso)
    	->get();

    	$sap = DB::table('incidencias as i')
    	->join('excel_SAP as es', 'i.id_es', '=', 'es.id_es')
    	->get();

    	foreach($tesoreria as $tes){
    		$textoTeso = "";
    		$formas = DB::table("formas_pago")
	    	->get();

	    	$encontro = false;

	    	if(is_integer($tes->FORMAP)){
	    		if((int)$tes->FORMAP == 3){
	    			$ctaord = str_replace(" ", "", $tes->CATABEN);
	    			$ctaord = str_replace("	", "", $ctaord);
	    			if(strlen($ctaord) == 10 || strlen($ctaord) == 18){
	    				if(!is_integer($ctaord)){
	    					$textoTeso .= "Si la forma de pago es 03, la cuenta del beneficiario debe contener solo d??gitos.<br>";
	    				}
	    			}
	    			else{
	    				$textoTeso .= "Si la forma de pago es 03, la cuenta del beneficiario debe contener 10 o 18 d??gitos.<br>";
	    			}
	    			$ctaord = str_replace(" ", "", $tes->CTAORD);
	    			$ctaord = str_replace("	", "", $ctaord);
	    			if(strlen($ctaord) < 10 || strlen($ctaord) > 50){
	    				$textoTeso .= "Si la forma de pago es 03, la cuenta del ordenante debe contener entre 10 y 50 d??gitos.<br>";
	    			}
	    		}
	    		elseif((int)$tes->FORMAP == 2){
	    			$ctaord = str_replace(" ", "", $tes->CATABEN);
	    			$ctaord = str_replace("	", "", $ctaord);
	    			if(strlen($ctaord) < 10 || strlen($ctaord) > 50){
	    				$textoTeso .= "Si la forma de pago es 02, la cuenta del beneficiario debe contener entre 10 y 50 d??gitos.<br>";
	    			}
	    			$ctaord = str_replace(" ", "", $tes->CTAORD);
	    			$ctaord = str_replace("	", "", $ctaord);
	    			if(strlen($ctaord) == 10 || strlen($ctaord) == 18){
	    				if(!is_integer($ctaord)){
	    					$textoTeso .= "Si la forma de pago es 02, la cuenta del ordenante debe contener solo d??gitos.<br>";
	    				}
	    			}
	    			else{
	    				$textoTeso .= "Si la forma de pago es 02, la cuenta del ordenante debe contener 10 o 18 d??gitos.<br>";
	    			}
	    		}
	    	}
	    	else{
	    		foreach($formas as $for){
			    	$equivalencias = explode(":", $for->equivalentes);
			    	foreach ($equivalencias as $equi){
			    		if($equi == $tes->FORMAP && $encontro == false){
			    			$pag = $for->id;
			    			$encontro = true;
			    		}
			    	}
			    }
			    if($encontro == true){
			    	if($pag == 3){
			    		$ctaord = str_replace(" ", "", $tes->CATABEN);
		    			$ctaord = str_replace("	", "", $ctaord);
		    			if(strlen($ctaord) == 10 || strlen($ctaord) == 18){
		    				if(!is_integer($ctaord)){
		    					$textoTeso .= "Si la forma de pago es 03, la cuenta del beneficiario debe contener solo d??gitos.<br>";
		    				}
		    			}
		    			else{
		    				$textoTeso .= "Si la forma de pago es 03, la cuenta del beneficiario debe contener 10 o 18 d??gitos.<br>";
		    			}
		    			$ctaord = str_replace(" ", "", $tes->CTAORD);
		    			$ctaord = str_replace("	", "", $ctaord);
		    			if(strlen($ctaord) < 10 || strlen($ctaord) > 50){
		    				$textoTeso .= "Si la forma de pago es 03, la cuenta del ordenante debe contener entre 10 y 50 d??gitos.<br>";
		    			}
		    		}
		    		elseif($pag == 2){
		    			$ctaord = str_replace(" ", "", $tes->CATABEN);
		    			$ctaord = str_replace("	", "", $ctaord);
		    			if(strlen($ctaord) < 10 || strlen($ctaord) > 50){
		    				$textoTeso .= "Si la forma de pago es 02, la cuenta del beneficiario debe contener entre 10 y 50 d??gitos.<br>";
		    			}
		    			$ctaord = str_replace(" ", "", $tes->CTAORD);
		    			$ctaord = str_replace("	", "", $ctaord);
		    			if(strlen($ctaord) == 10 || strlen($ctaord) == 18){
		    				if(!is_integer($ctaord)){
		    					$textoTeso .= "Si la forma de pago es 02, la cuenta del ordenante debe contener solo d??gitos.<br>";
		    				}
		    			}
		    			else{
		    				$textoTeso .= "Si la forma de pago es 02, la cuenta del ordenante debe contener 10 o 18 d??gitos.<br>";
		    			}
		    		}
		    		else{
				    	$textoTeso .= "No hay una forma de pago especificada. Debe ser un numero de 2 d??gitos.";
				    }
			    }
	    	}

	    	$inciTeso = new Incidencias_Tesoreria_Model;
	    	$inciTeso->linea = $tes->id_tt;
	    	$inciTeso->incidencias = $textoTeso;
	    	$inciTeso->nombre_archivo = $tes->nombre;
	    	$inciTeso->fecha = $tes->fecha;
	    	$inciTeso->save();
    	}

    	foreach($sap as $s) {
    		$inciSAP = new Incidencias_SAP_Model;
	    	$inciSAP->clearing = $s->clearing_document;
	    	$inciSAP->incidencias = $s->timbrado;
	    	$inciSAP->nombre_archivo = $s->nombre;
	    	$inciSAP->fecha = $s->fecha;
	    	$inciSAP->save();
    	}

    	return redirect()->action('ArchviosController@mostrarIncidencias');
    }

    public function mostrarIncidencias()
    {
    	$iSAP = DB::table("incidencias_SAP")
    	->get();

    	$iTeso = DB::table("incidencias_tesoreria")
    	->get();

    	return view('correccionDeIncidencias', ["iSAP" => $iSAP, "iTeso" => $iTeso]);
    }
    public function buscarIncidenciasSAP(Request $request){
    	$sentencia = "";

    	if($request->has("inicio") && $request->has("fin")){
    		if($request->get("inicio") != "" && $request->get("fin") != ""){
    			$sentencia = "fecha between '".$request->get("inicio")."' and '".$request->get("fin")."' ";
    			if($request->has("archivo") && $request->get("archivo") != ""){
    				$sentencia.="and nombre_archivo like '%".$request->get("cliente")."%'";
    			}
    		}
    		else{
    			if($request->has("cliente") && $request->get("cliente") != ""){
    				$sentencia.="nombre_archivo like '%".$request->get("cliente")."%'";
    			}
    		}
    	}
    	else{
    		if($request->has("cliente") && $request->get("cliente") != ""){
    			$sentencia.="nombre_archivo like '%".$request->get("cliente")."%'";
    		}
    	}

    	if($sentencia != ""){
    		$iSAP = DB::table("incidencias_SAP")
    		->whereRaw(DB::raw($sentencia))
    		->get();

    		return response()->json($iSAP);
    	}
    }

    public function crearExcelSAP(Request $request)
    {
    	$orderItems = Incidencias_SAP_Model::all();
		Excel::create("incidenciasSAP", function ($excel) use ($orderItems) {
			$excel->setTitle("Title");
			$excel->sheet("Sheet 1", function ($sheet) use ($orderItems) {
				$sheet->fromArray($orderItems);
			});
		})->download('xls');
		return back();
    }

    public function crearExcelTesoreria(Request $request)
    {
    	$orderItems = Incidencias_Tesoreria_Model::all();
		Excel::create("incidenciasTesoreria", function ($excel) use ($orderItems) {
			$excel->setTitle("Title");
			$excel->sheet("Sheet 1", function ($sheet) use ($orderItems) {
				$sheet->fromArray($orderItems);
			});
		})->download('xls');
		return back();
    }

    public function archivosTeso(Request $request)
    {
    	$fechaInicio = $request->get('inicio');
    	$fechaFin = $request->get('fin');
    	$opcion = $request->get('opcion');
    	$busqueda = "";

    	switch ($opcion) {
    		case 1:
    			$busqueda = DB::table('excel_tesoreria')
    			->where('integrado', '=', 0)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_tesoreria')
	    			->where('integrado', '=', 0)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;

    		case 2:
    			$busqueda = DB::table('excel_tesoreria')
    			->where('integrado', '=', 1)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_tesoreria')
	    			->where('integrado', '=', 1)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;

    		case 3:
    			$busqueda = DB::table('excel_SAP')
    			->where('integrado', '=', 0)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_SAP')
	    			->where('integrado', '=', 0)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;

    		case 4:
    			$busqueda = DB::table('excel_SAP')
    			->where('integrado', '=', 1)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_SAP')
	    			->where('integrado', '=', 1)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;

    		case 5:
    			$busqueda = DB::table('excel_credito')
    			->where('integrado', '=', 0)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_credito')
	    			->where('integrado', '=', 0)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;

    		case 6:
    			$busqueda = DB::table('excel_credito')
    			->where('integrado', '=', 1)
		    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
		    	->count();

		    	if($busqueda < 1){
		    		return response()->json([
		    			"respuesta" => 2
		    		]);
		    	}
		    	else{
		    		$busqueda = DB::table('excel_credito')
	    			->where('integrado', '=', 1)
			    	->whereBetween('fecha', [$fechaInicio, $fechaFin])
			    	->get();

		    		return response()->json($busqueda);
		    	}
    			break;
    		
    		default:
    			return response()->json([
		    			"respuesta" => 2
		    		]);
    			break;
    	}
    }

    public function eliminarArchivo(Request $request)
    {
    	$busqueda = "";

    	switch ($request->opcion) {
    		case 1:
    			$busqueda = DB::table('excel_tesoreria')
    			->where('id_et', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("tesoreria")
		    	->where("id_et", "=", $request->get("archivo"))
		    	->delete();

		    	$busqueda = DB::table('excel_tesoreria')
	    		->where('integrado', '=', 0)
			    ->get();

    			break;

    		case 2:
    			$busqueda = DB::table('excel_tesoreria')
    			->where('id_et', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("tesoreria")
		    	->where("id_et", "=", $request->get("archivo"))
		    	->delete();

		    	$busqueda = DB::table('excel_tesoreria')
	    		->where('integrado', '=', 0)
			    ->get();

		    	return response()->json($busqueda);

    			break;

    		case 3:
    			$busqueda = DB::table('excel_sap')
    			->where('id_es', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("pago")
		    	->where("id_es", "=", $request->get("archivo"))
		    	->delete();

		    	DB::table("parcialidades")
		    	->where("id_es", "=", $request->get("archivo"))
		    	->delete();

    			$busqueda = DB::table('excel_sap')
	    		->where('integrado', '=', 0)
			    ->get();

    			break;

    		case 4:
		    	$busqueda = DB::table('excel_sap')
    			->where('id_es', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("pago")
		    	->where("id_es", "=", $request->get("archivo"))
		    	->delete();

		    	DB::table("parcialidades")
		    	->where("id_es", "=", $request->get("archivo"))
		    	->delete();

		    	$busqueda = DB::table('excel_sap')
	    		->where('integrado', '=', 0)
			    ->get();

    			break;

    		case 5:
    			$busqueda = DB::table('excel_credito')
    			->where('id_ec', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("credito")
		    	->where("id_ec", "=", $request->get("archivo"))
		    	->delete();

		    	DB::table("facturas_liquidadas")
		    	->where("id_ec", "=", $request->get("archivo"))
		    	->delete();

		    	$busqueda = DB::table('excel_credito')
	    		->where('integrado', '=', 0)
			    ->get();

    			break;

    		case 6:
    			$busqueda = DB::table('excel_credito')
    			->where('id_ec', '=', $request->get("archivo"))
		    	->delete();

		    	DB::table("credito")
		    	->where("id_ec", "=", $request->get("archivo"))
		    	->delete();

		    	DB::table("facturas_liquidadas")
		    	->where("id_ec", "=", $request->get("archivo"))
		    	->delete();

		    	$busqueda = DB::table('excel_credito')
	    		->where('integrado', '=', 0)
			    ->get();

    			break;
    		
    		default:
    			return response()->json([
		    			"respuesta" => 2
		    		]);
    			break;
    	}

    	return response()->json($busqueda);
    }
}
