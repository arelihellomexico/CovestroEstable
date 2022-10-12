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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ComplementoController extends Controller
{
    public function crearComplemento()
    {
    	$mensaje = "";
    	$covestro = DB::table("covestro")
    	->first();

    	$sap = DB::table("pago")
	    ->where("timbrado", "=", "0")
	    ->get();

	    foreach ($sap as $s) {
	    	if ($s->rfc_c != "") {
	    		$tesoreria = DB::table("tesoreria")
			    ->where("timbrado", "=", "0")
			    ->get();
		    	foreach ($tesoreria as $t) {
		    		if($s->montoP == $t->MONTOP){
		    			$simi = new Similaridades;
		    			$simi->RFC_R = $t->RFC_R;
						$simi->MONTOP = $t->MONTOP;
						$simi->MONEDAP = $t->MONEDAP;
						$simi->NUMEROPERP = $t->NUMEROPERP;
						$simi->RFCCTABEN = $t->RFCCTABEN;
						$simi->CATABEN = $t->CATABEN;
						$simi->FORMAP = $t->FORMAP;
						$simi->RFCCTAORD = $t->RFCCTAORD;
						$simi->BANCOORDEXT = $t->BANCOORDEXT;
						$simi->CTAORD = $t->CTAORD;
						$simi->FECHAPAG = $t->FECHAPAG;
						$simi->save();
		    		}
		    	}

		    	$conteo = DB::table("similaridades")
		    	->count();

		    	$simil = DB::table("similaridades")
		    	->get();

		    	if($conteo == 0){
		    		$incidencia = new Incidencias;
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
					$incidencia->rfc_e = $s->rfc_e;
					$incidencia->timbrado = $s->timbrado;
					$incidencia->save();

					DB::table("pago")
					->where('clearing_document', '=', $s->clearing_document)
					->update(["timbrado" => "No monto"]);
		    	}
		    	if($conteo == 1){
		    		$complemento = new Complemento;
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
					$complemento->tipocambioP = $s->tipocambioP;					
					$complemento->montoP = $s->montoP;
					$complemento->signo = $s->signo;
					$complemento->numeroperP = $simil[0]->NUMEROPERP;
					$complemento->rfcctaord = $simil[0]->RFCCTAORD;
					$complemento->bancoordext = $simil[0]->BANCOORDEXT;
					$complemento->ctaord = $simil[0]->CTAORD;
					$complemento->rfcctaben = $simil[0]->RFCCTABEN;
					$complemento->cataben = $simil[0]->CATABEN;
					$complemento->rfc_c = $s->rfc_c;
					$complemento->rfc_e = $s->rfc_e;
					$complemento->timbrado = $s->timbrado;
					$complemento->save();

					DB::table("pago")
					->where('clearing_document', '=', $s->clearing_document)
					->update(["timbrado" => "1"]);

					DB::table("tesoreria")
					->where('id_tt', '=', $simil[0]->id_tt)
					->update(["timbrado" => "1"]);

					DB::table("similaridades")
					->delete();
		    	}
		    	if($conteo > 1){
		    		$borrar = DB::table("similaridades")
		    		->delete();
		    		foreach ($simil as $monedas) {
		    			if($s->monedaP == $monedas->MONEDAP){
		    				$simi = new Similaridades;
			    			$simi->RFC_R = $monedas->RFC_R;
							$simi->MONTOP = $monedas->MONTOP;
							$simi->MONEDAP = $monedas->MONEDAP;
							$simi->NUMEROPERP = $monedas->NUMEROPERP;
							$simi->RFCCTABEN = $monedas->RFCCTABEN;
							$simi->CATABEN = $monedas->CATABEN;
							$simi->FORMAP = $monedas->FORMAP;
							$simi->RFCCTAORD = $monedas->RFCCTAORD;
							$simi->BANCOORDEXT = $monedas->BANCOORDEXT;
							$simi->CTAORD = $monedas->CTAORD;
							$simi->FECHAPAG = $monedas->FECHAPAG;
							$simi->save();
		    			}
		    		}

		    		$conteo = DB::table("similaridades")
			    	->count();

			    	$simil = DB::table("similaridades")
			    	->get();

			    	if($conteo == 0){
			    		$incidencia = new Incidencias;
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
						$incidencia->rfc_e = $s->rfc_e;
						$incidencia->timbrado = $s->timbrado;
						$incidencia->save();

						DB::table("pago")
						->where('clearing_document', '=', $s->clearing_document)
						->update(["timbrado" => "No Moneda"]);
			    	}
			    	if($conteo == 1){
			    		$complemento = new Complemento;
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
						$complemento->tipocambioP = $s->tipocambioP;					
						$complemento->montoP = $s->montoP;
						$complemento->signo = $s->signo;
						$complemento->numeroperP = $simil[0]->NUMEROPERP;
						$complemento->rfcctaord = $simil[0]->RFCCTAORD;
						$complemento->bancoordext = $simil[0]->BANCOORDEXT;
						$complemento->ctaord = $simil[0]->CTAORD;
						$complemento->rfcctaben = $simil[0]->RFCCTABEN;
						$complemento->cataben = $simil[0]->CATABEN;
						$complemento->rfc_c = $s->rfc_c;
						$complemento->rfc_e = $s->rfc_e;
						$complemento->timbrado = $s->timbrado;
						$complemento->save();

						DB::table("pago")
						->where('clearing_document', '=', $s->clearing_document)
						->update(["timbrado" => "1"]);

						DB::table("tesoreria")
						->where('id_tt', '=', $simil[0]->id_tt)
						->update(["timbrado" => "1"]);
			    	}
			    	if($conteo > 1){
			    		$borrar = DB::table("similaridades")
			    		->delete();
			    		foreach ($simil as $rfcs) {
			    			if($s->rfc_c == $rfcs->RFC_R){
			    				$simi = new Similaridades;
				    			$simi->RFC_R = $rfcs->RFC_R;
								$simi->MONTOP = $rfcs->MONTOP;
								$simi->MONEDAP = $rfcs->MONEDAP;
								$simi->NUMEROPERP = $rfcs->NUMEROPERP;
								$simi->RFCCTABEN = $rfcs->RFCCTABEN;
								$simi->CATABEN = $rfcs->CATABEN;
								$simi->FORMAP = $rfcs->FORMAP;
								$simi->RFCCTAORD = $rfcs->RFCCTAORD;
								$simi->BANCOORDEXT = $rfcs->BANCOORDEXT;
								$simi->CTAORD = $rfcs->CTAORD;
								$simi->FECHAPAG = $rfcs->FECHAPAG;
								$simi->save();
			    			}
			    		}
			    		
			    		$conteo = DB::table("similaridades")
				    	->count();

				    	$simil = DB::table("similaridades")
				    	->get();

				    	if($conteo == 0){
				    		$incidencia = new Incidencias;
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
							$incidencia->rfc_e = $s->rfc_e;
							$incidencia->timbrado = $s->timbrado;
							$incidencia->save();

							DB::table("pago")
							->where('clearing_document', '=', $s->clearing_document)
							->update(["timbrado" => "No Cliente"]);
				    	}
				    	if($conteo >= 1){
				    		$complemento = new Complemento;
					    	$complemento->clearing_document = $s->clearing_document;
							$complemento->version = $s->version;
							$complemento->fecha_clearing = $s->fecha_clearing;
							$complemento->regimen = $s->regimen;
							$complemento->lugarexpedicion = $s->lugarexpedicion;
							$complemento->residenciafiscal = $s->residenciafiscal;
							$complemento->numregidtrib = $s->numregidtrib;
							$complemento->confirmacion = $s->confirmacion;
							$complemento->formap = $simi->FORMAP;
							$complemento->monedaP = $s->monedaP;
							$complemento->fechap = $simi->FECHAPAG;
							$complemento->tipocambioP = $s->tipocambioP;					
							$complemento->montoP = $s->montoP;
							$complemento->signo = $s->signo;
							$complemento->numeroperP = $simi->NUMEROPERP;
							$complemento->rfcctaord = $simi->RFCCTAORD;
							$complemento->bancoordext = $simi->BANCOORDEXT;
							$complemento->ctaord = $simi->CTAORD;
							$complemento->rfcctaben = $simi->RFCCTABEN;
							$complemento->cataben = $simi->CATABEN;
							$complemento->rfc_c = $s->rfc_c;
							$complemento->rfc_e = $s->rfc_e;
							$complemento->timbrado = $s->timbrado;
							$complemento->save();

							DB::table("pago")
							->where('clearing_document', '=', $s->clearing_document)
							->update(["timbrado" => "1"]);

							DB::table("tesoreria")
							->where('id_tt', '=', $simil[0]->id_tt)
							->update(["timbrado" => "1"]);
				    	}
			    	}
		    	}
	    	}
	    	else{
	    		$incidencia = new Incidencias;
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
				$incidencia->rfc_e = $s->rfc_e;
				$incidencia->timbrado = $s->timbrado;
				$incidencia->save();
	    	}
	    }

	    $tesoinci = DB::table("tesoreria")
	    ->where("timbrado", '=', '0')
	    ->get();

	    $clearings = DB::table("complemento")
	    ->select("clearing_document")
	    ->get(); 

	    /*$facnoencontradas = DB::table("factura as f")
	    ->join("parcialidades as p", "f.folio", "=", "p.folio")
	    ->whereNotIn("f.clearing_document", $clearings)
	    ->get();*/

	    $correctos = DB::table("complemento")
	    ->get();

	    $incidentes = DB::table("incidencias")
	    ->get();

	    $facturas = DB::table("factura")
	    ->get();

	    $parcialidades = DB::table("parcialidades")
	    ->get();

	    foreach($correctos as $cor){
	    	$cliente = DB::table("clientes")
	    	->where('rfc_c', '=', $cor->rfc_c)
	    	->first();
	    	/*$encontro = false;
	    	$pag = "";
	    	$formas = DB::table("formas_pago")
	    	->get();

	    	foreach($formas as $for){
	    		$equivalencias = explode(",", $for->equivalencias);
	    		foreach ($equivalencias as $equi){
	    			if($equi == $cor->formap && $encontro == false){
	    				$pag = $for->id;
	    				$encontro = true;
	    			}
	    		}
	    	}*/

	    	$nombre_archivo = "C://xampp/htdocs/Covestro/Covestro/covestro/timbrados/".$cor->rfc_c."_".$cor->clearing_document.".txt";
	    	$fecha = str_replace("/", "", $cor->fechap);
	    	$mensaje = "CABPAGOS|1.0||".$cor->clearing_document."|FECHA_PENDIENTE|".$cor->regimen."|".$cor->rfc_e."|".$covestro->nombre_e."|".$covestro->calle_e."|".$covestro->numext_e."|".$covestro->numint_e."|".$covestro->colonia_e."|".$covestro->localidad_e."||".$covestro->municipio_e."|".$covestro->estado_e."|".$covestro->pais_e."|".$covestro->cpostal_e."|".$covestro->cpostal_e."|".$cor->rfc_c."|".$cliente->nombre_c."|||||||||||".$cliente->residenciafiscal."|".$cliente->numregidtrib."|\n";
	    	
	    	foreach ($facturas as $f) {
	    		if($f->clearing_document == $cor->clearing_document){
	    			$mensaje.="CFDIREL|".$f->folio."\n";
	    		}
	    	}

	    	$mensaje.="CPAGO|1|".$fecha."|".$cor->formap."|".$cor->monedaP."|\n";
	    	$parci = DB::table("factura as f")
	    	->join("parcialidades as p", 'f.folio', '=', 'p.folio')
	    	->where("f.clearing_document", "=", $cor->clearing_document)
		    ->get();
	    	foreach ($parci as $par) {
	    		$mensaje.="CPAGODR|1|".$par->folio."||".substr($par->folio, -7)."|".$par->moneda."|".$par->tipcambio."|PUE|".$par->numparcialidad."|".$par->impsaldoant."|".$par->imppagado."|".$par->impsaldoins."\n";
	    	}

	    	if(file_exists($nombre_archivo)) {
 				$existe = "El archivo $nombre_archivo se ha modificado.";
			}else{
 				$existe = "El archivo $nombre_archivo ha creado";
			}

	    	if($archivo = fopen($nombre_archivo, "a")){
				if(fwrite($archivo, $mensaje)) {
					$hola = true;
				}else{
					$hola = false;
				}
				fclose($archivo);
			}
	    }


	    return view("paymentComplements", ["tesoinci" =>$tesoinci, "correctos" => $correctos, "incidentes" => $incidentes, "facturas" => $facturas, "parcialidades" => $parcialidades]);
    }
}
