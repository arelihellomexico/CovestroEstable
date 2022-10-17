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
use App\Procesos;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class ComplementoController extends Controller
{

	public function generarTxtCorrectos(Request $request)
	{
		$quepasa = "";
		$checalo = "";
		$larespuest="";
		try {
			$proceso = DB::table('procesos')
				->max('id_pro'); //adquiere el id_pro de mayor valor

			$archivosUsados = DB::table('procesos')
				->where('id_pro', '=', $proceso) //se busca en especifico los datos del proceso
				->first();

			$buSAP = explode(",", $archivosUsados->id_es);
			$buCred = explode(",", $archivosUsados->id_ec);

			$busquedaSAP = "";
			$busquedaCred = "";

			foreach ($buSAP as $bs) {
				$busquedaSAP .= "id_es = " . $bs . " or ";
			}
			$busquedaSAP .= "a"; //se le asigna de la siguiente manera: id_es=$bs or a

			foreach ($buCred as $bc) {
				$busquedaCred .= "id_ec = " . $bc . " or ";
			}
			$busquedaCred .= "a";
			$checalo = $busquedaSAP;
			$busquedaSAP = substr(str_replace("or a", "", $busquedaSAP), 0, 12); //no captura realmente nada $busquedaSAP = substr(str_replace("or a", "", $busquedaCred), 0, -12)
			$busquedaCred = substr(str_replace("or a", "", $busquedaCred), 0, 12); //modificado 09/04/2021

			$covestro = DB::table("covestro")
				->first(); //obtiene lo unico que hay en esa tabla

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
				->where('id_pro', '=', $proceso)
				->get(); //obtine la fila donde se esta realizando el proceso con el id_pro como identificador

			$incidentes = DB::table("incidencias")
				->get();

			$facturas = DB::table("factura")
				->get();

			$parcialidades = DB::table("parcialidades")
				->get(); //obtine todas las parcialidades

			$total = 0;

			$ruta = "../ARCHIVOS/SALIDA_TXT";
			//mkdir($ruta);

			$files = glob('../ARCHIVOS/SALIDA_TXT/*'); //obtenemos todos los nombres de los ficheros
			foreach ($files as $file) {
				if (is_file($file))
					unlink($file); //elimino el fichero
			}

			foreach ($correctos as $cor) {
				DB::table("complemento")
					->where('id_comp', '=', $cor->id_comp)
					->update([
						"fecha_clearing" => date("Y-m-d H:i:s") //actualiza la fecha para el complemento con fecha y hora
					]);

				$total = $total + 1;
				$cliente = DB::table("clientes")
					->where('rfc_c', '=', $cor->rfc_c)
					->first();
				$encontro = false;
				$tipo = "";
				$pag = "";
				$formas = DB::table("formas_pago")
					->get();

				if ($cor->monedaP == "MXN") {
					$tipo = "";
				} else {
					$tipo = $cor->tipocambioP;
				}

				if (strlen($cor->formap) == 1) {
					$pag = "0" . $cor->formap;
				} elseif ($pag == "") {
					$pag = $cor->formap;
				} else {
					foreach ($formas as $for) {
						$equivalencias = explode(":", $for->equivalentes);
						foreach ($equivalencias as $equi) {
							if ($equi == $cor->formap && $encontro == false) {
								if (strlen($for->id) == 1) {
									$pag = "0" . $for->id;
								} else {
									$pag = $for->id;
								}
								$encontro = true;
							}
						}
					}
				}

				$nombre_archivo = $ruta . "/" . $cor->rfc_c . "_" . $cor->clearing_document . ".txt"; //nombre del archivo
				if (strlen($cor->fechap) == 23) {
					$separacion = explode(" ", $cor->fechap);
					$date = explode("-", $separacion[0]);
					$date = $date[2] . "" . $date[1] . "" . $date[0];
					if ($separacion[1] == "00:00:00.000") {
						$time = "120000";
					} else {
						$time = str_replace(":", "", $separacion[1]);
						$time = str_replace(".000", "", $time);
					}
					$fecha = $date . $time;
				} else {
					$fecha =  $cor->fechap;
				}
				if ($cor->residenciafiscal != null) { //si es nulo se crea el primer texto para el archivo txt
					$mensaje = "CABPAGOS|" . $covestro->version_complemento . "||" . $cor->clearing_document . "|" . date("dmYHis") . "|" . $cor->regimen . "|" . $covestro->rfc_e . "|" . $covestro->nombre_e . "|" . $covestro->calle_e . "|" . $covestro->numext_e . "|" . $covestro->numint_e . "|" . $covestro->colonia_e . "|" . $covestro->localidad_e . "||" . $covestro->municipio_e . "|" . $covestro->estado_e . "|" . $covestro->pais_e . "|" . $covestro->cpostal_e . "|" . $covestro->cpostal_e . "|XEXX010101000|" . $cor->nombre_c . "|||||||||||" . $cor->residenciafiscal . "|" . $cor->numregidtrib . "|\r\n";
				} else {
					$mensaje = "CABPAGOS|" . $covestro->version_complemento . "||" . $cor->clearing_document . "|" . date("dmYHis") . "|" . $cor->regimen . "|" . $covestro->rfc_e . "|" . $covestro->nombre_e . "|" . $covestro->calle_e . "|" . $covestro->numext_e . "|" . $covestro->numint_e . "|" . $covestro->colonia_e . "|" . $covestro->localidad_e . "||" . $covestro->municipio_e . "|" . $covestro->estado_e . "|" . $covestro->pais_e . "|" . $covestro->cpostal_e . "|" . $covestro->cpostal_e . "|" . $cor->rfc_c . "|" . $cor->nombre_c . "|||||||||||" . $cor->residenciafiscal . "|" . $cor->numregidtrib . "|\r\n";
				}

				foreach ($parcialidades as $f) { //no hace nada
					if ($f->clearing_document == $cor->clearing_document) {
						//$mensaje.="CFDIREL|".$f->folio."\r\n";
					}
				}

				if (strlen($cor->ctaord) > 18) {
					$cuentaord = "";
				} else {
					$cuentaord = $cor->ctaord;
				}

				$elmonto = explode(".", $cor->montoP);
				$montodec = 0;

				if(sizeof($elmonto) == 1) { // anterior
					$montodec = $elmonto[0] . '.00';
				} else {
					if(strlen($elmonto[1]) == 0) {
						$montodec = $elmonto[0] . '.00';
					} elseif(strlen($elmonto[1]) == 1) {
						$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
					} else {
						$montodec = $cor->montoP;
					}
				}

				if ($cor->rfcctaben == "XEXX010101000") {
					if (strlen($cor->cataben) < 10 && $cor->cataben != "" && !is_null($cor->cataben)) {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|0" . $cor->cataben . "\r\n";
					} else {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|" . $cor->cataben . "\r\n";
					}
				} else {
					if (strlen($cor->cataben) < 10 && $cor->cataben != "" && !is_null($cor->cataben)) {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|0" . $cor->cataben . "\r\n";
					} else {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|" . $cor->cataben . "\r\n";
					}
				}

				if ($covestro->usar_credito == 1) {
					$parci = DB::table("credito")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(' . $busquedaCred . ')'))
						->get();
					foreach ($parci as $par) {
						if ($par->moneda == $cor->monedaP) {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						} else {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						}
					}
					$parci = DB::table("facturas_liquidadas")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(id_es=' . str_replace(",", "", $archivosUsados->id_es) . ')'))
						->get();
					//aqui se le asigna al txt el tipo de cambio
					foreach ($parci as $par) {
						if ($par->moneda == $cor->monedaP) {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						} else {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						}
					}
				} else {
					$parci = DB::table("parcialidades")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(id_es=' . str_replace(",", "", $archivosUsados->id_es) . ')'))
						->get();
					foreach ($parci as $par) {
						$impanterior = explode(".", $par->impsaldoant);
						$impagado = explode(".", $par->imppagado);
						$impinsoluto = explode(".", $par->impsaldoins);
						$anterior = 0;
						$pagado = 0;
						$insoluto = 0;

						if(sizeof($impanterior) == 1) { // anterior
							$anterior = $impanterior[0] . '.00';
						} else {
							if(strlen($impanterior[1]) == 0) {
								$anterior = $impanterior[0] . '.00';
							} elseif(strlen($impanterior[1]) == 1) {
								$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
							} else {
								$anterior = $par->impsaldoant;
							}
						}
						if(sizeof($impagado) == 1) { // pagado
							$pagado = $impagado[0] . '.00';
						} else {
							if(strlen($impagado[1]) == 0) { // pagado
								$pagado = $impagado[0] . '.00';
							} elseif(strlen($impagado[1]) == 1) {
								$pagado = $impagado[0] . '.' . $impagado[1] .'0';
							} else {
								$pagado = $par->imppagado;
							}
						}
						if(sizeof($impinsoluto) == 1) { // insoluto
							$insoluto = $impinsoluto[0] . '.00';
						} else {
							if(strlen($impinsoluto[1]) == 0) { // insoluto
								$insoluto = $impinsoluto[0] . '.00';
							} elseif(strlen($impinsoluto[1]) == 1) {
								$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
							} else {
								$insoluto = $par->impsaldoins;
							}
						}

						if ($par->moneda == $cor->monedaP) {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $anterior . "|" . $pagado . "|" . $insoluto . "\r\n";
						} else {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $anterior . "|" . $pagado . "|" . $insoluto . "\r\n";
						}
					}
					/*try {
						$parci = DB::table("parcialidades")
							->where("clearing_document", "=", $cor->clearing_document)
							->whereRaw(DB::raw('(' . $busquedaSAP . ')'))
							->get();
						foreach ($parci as $par) {
							if ($par->moneda == $cor->monedaP) {
								$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
							} else {
								$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $covestro->metpago . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
							}
						}
					} catch (\Exception $mot) {
						$checalo = $mot->getMessage();
					}*/
				}


				if ($archivo = fopen($nombre_archivo, "a")) { //crea el nuevo archivo txt
					if (fwrite($archivo, $mensaje)) { //escribe en el archivo txt
						$hola = true;
					} else {
						$hola = false;
					}
					fclose($archivo);
				}

				$archivo = new Archivos;
				$archivo->nombre = $cor->rfc_c . "_" . $cor->clearing_document . ".txt";
				$archivo->fecha = date('Y-m-d');
				$archivo->fechabus = $cor->fechabus;
				$archivo->ruta = "../ARCHIVOS/PROCESADOS_TXT/";
				$archivo->timbrado = "0";
				$archivo->clearing = $cor->clearing_document;
				$archivo->id_cliente = $cor->id_cliente;
				$archivo->rfc_cliente = $cor->rfc_c;
				$archivo->cliente = $cor->nombre_c;
				$archivo->id_pro = $cor->id_pro;
				$archivo->generapdf = $cor->rfc_c . "_" . $cor->clearing_document . ".pdf";
				$archivo->generaxml = $cor->rfc_c . "_" . $cor->clearing_document . ".xml";
				$archivo->save();
			}
			$cuantos = DB::table("complemento")
				->count();
			DB::table("procesos")
				->where("id_pro", "=", $proceso)
				->update([
					"total" => $total,
					"integracion" => 0,
					"timbrado" => 1,
					"obtencion" => 0
				]); // se guarda la actualizacion cambia el parametro timbrado a uno
			if ($cuantos == 0) {
				$larespuest = "2";
			} else {
				$larespuest = "si";
			}
			$quepasa = "todo chido";
		} catch (\Exception $th) {
			$quepasa = $th->getMessage();
			$larespuest = "0";
			$proceso = DB::table('procesos')
				->max('id_pro'); //adquiere el id_pro de mayor valor

			$archivosUsados = DB::table('procesos')
				->where('id_pro', '=', $proceso) //se busca en especifico los datos del proceso
				->first();

			$buSAP = explode(",", $archivosUsados->id_es);
			$buCred = explode(",", $archivosUsados->id_ec);

			$busquedaSAP = "";
			$busquedaCred = "";

			foreach ($buSAP as $bs) {
				$busquedaSAP .= "id_es = " . $bs . " or ";
			}
			$busquedaSAP .= "a"; //se le asigna de la siguiente manera: id_es=$bs or a

			foreach ($buCred as $bc) {
				$busquedaCred .= "id_ec = " . $bc . " or ";
			}
			$busquedaCred .= "a";

			$busquedaSAP = substr(str_replace("or a", "", $busquedaSAP), 0, 12); //no captura realmente nada $busquedaSAP = substr(str_replace("or a", "", $busquedaCred), 0, -12)
			$busquedaCred = substr(str_replace("or a", "", $busquedaCred), 0, 12); //modificado 09/04/2021
			$checalo = str_replace(",", "", $archivosUsados->id_es);
		}
		return response()->json([
			"respuesta" => $larespuest,
			"mensaje" => $quepasa,
			"otrmens" => $checalo
		]);
	}

	public function generarTxtErroneos(Request $request)
	{
		$covestro = DB::table("covestro")
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
			->get();

		$incidentes = DB::table("incidencias")
			->get();

		$facturas = DB::table("factura")
			->get();

		$parcialidades = DB::table("parcialidades")
			->get();

		foreach ($incidentes as $cor) {
			$cliente = DB::table("clientes")
				->where('rfc_c', '=', $cor->rfc_c)
				->first();
			$encontro = false;
			$pag = "";
			$formas = DB::table("formas_pago")
				->get();

			foreach ($formas as $for) {
				$equivalencias = explode(":", $for->equivalentes);
				foreach ($equivalencias as $equi) {
					if ($equi == $cor->formap && $encontro == false) {
						$pag = $for->id;
						$encontro = true;
					}
				}
			}

			if ($pag == "") {
				$pag = $cor->formap;
			}

			$nombre_archivo = "C://xampp/htdocs/COMPLEMENTOPAGOS/ARCHIVOS/SALIDA_TXT/" . $cor->rfc_c . "_" . $cor->clearing_document . ".txt";
			$fecha = str_replace("/", "", $cor->fechap);
			$mensaje = "CABPAGOS|1.0||" . $cor->clearing_document . "|FECHA_PENDIENTE|" . $cor->regimen . "|" . $cor->rfc_e . "|" . $covestro->nombre_e . "|" . $covestro->calle_e . "|" . $covestro->numext_e . "|" . $covestro->numint_e . "|" . $covestro->colonia_e . "|" . $covestro->localidad_e . "||" . $covestro->municipio_e . "|" . $covestro->estado_e . "|" . $covestro->pais_e . "|" . $covestro->cpostal_e . "|" . $covestro->cpostal_e . "|" . $cor->rfc_c . "|" . $cliente->nombre_c . "|||||||||||" . $cliente->residenciafiscal . "|" . $cliente->numregidtrib . "|\r\n";


			foreach ($facturas as $f) {
				if ($f->clearing_document == $cor->clearing_document) {
					$mensaje .= "CFDIREL|" . $f->folio . "\r\n";
				}
			}

			$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $cor->tipocambioP . "|" . $cor->montoP . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cor->ctaord . "|" . $cor->rfcctaben . "|" . $cor->cataben . "\r\n";
			$parci = DB::table("factura as f")
				->join("parcialidades as p", 'f.folio', '=', 'p.folio')
				->where("f.clearing_document", "=", $cor->clearing_document)
				->get();
			foreach ($parci as $par) {
				$mensaje .= "CPAGODR|1|" . $par->folio . "||" . substr($par->folio, -7) . "|USD||PUE|" . $par->numparcialidad . "|" . str_replace("si$", "", $par->impsaldoant) . "|" . str_replace("p$", "", $par->imppagado) . "|" . $par->impsaldoins . "\r\n";
			}

			if (file_exists($nombre_archivo)) {
				$existe = "El archivo $nombre_archivo se ha modificado.";
			} else {
				if ($archivo = fopen($nombre_archivo, "a")) {
					if (fwrite($archivo, $mensaje)) {
						$hola = true;
					} else {
						$hola = false;
					}
					fclose($archivo);
				}
			}



			$archivo = new Archivos;
			$archivo->nombre = $cor->rfc_c . "_" . $cor->clearing_document . ".txt";
			$archivo->ruta = "../ARCHIVOS/ENTRADA_TXT/";
			$archivo->timbrado = "0";
			$archivo->generapdf = "0";
			$archivo->generaxml = "0";
			$archivo->save();
		}

		$clear701 = DB::table("parcialidades")
			->select("clearing_document")
			->groupBy("clearing_document")
			->get();

		foreach ($clear701 as $c701) {
		}

		return response()->json([
			"respuesta" => "si"
		]);
	}

	public function checklist()
	{
		$archivos = DB::table("archivos")
			->get();

		return view("StatusComplements", ["archivos" => $archivos]);
	}

	public function descargar($id)
	{
		$archivos = DB::table("archivos")
			->where("id_ar", "=", $id)
			->first();

		$pathtoFile = "../ARCHIVOS/PROCESADOS_TXT/" . $archivos->nombre;
		return response()->download($pathtoFile);
	}

	public function descargarXML($id)
	{
		$archivos = DB::table("archivos")
			->where("id_ar", "=", $id)
			->first();

		$pathtoFile = "../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/" . $archivos->generaxml;
		return response()->download($pathtoFile);
	}

	public function descargarPDF($id)
	{
		$archivos = DB::table("archivos")
			->where("id_ar", "=", $id)
			->first();

		$pathtoFile = "../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/" . $archivos->generapdf;
		return response()->download($pathtoFile);
	}

	public function eliminarTimbrado(Request $request)
	{
		$mostrar = DB::table("tesoreria")
			->delete();

		$mostrar = DB::table("pago")
			->delete();

		$mostrar = DB::table("factura")
			->delete();

		$mostrar = DB::table("parcialidades")
			->delete();

		$mostrar = DB::table("similaridades")
			->delete();

		$mostrar = DB::table("incidencias")
			->delete();

		$mostrar = DB::table("complemento")
			->delete();

		$mostrar = DB::table("archivos")
			->delete();

		return response()->json([
			"respuesta" => "si"
		]);
	}

	public function generarTxtCorrectosEsp(Request $request)
	{
		$quepasa = "";
		$checalo = "";
		$larespuest="";

		$dividir = explode("&", $request->get("decimales"));
		$decimal = explode("=", $dividir[1]);

		// return response()->json([
		// 	"respuesta" => 5,
		// 	"decimales" => $decimal[1],
		// 	"metodo" => $request->get("MetodoDePagoDR"),
		// 	"forma p" => $request->get("FormatoDePagoP"),
		// ]);

		try {
			$proceso = DB::table('procesos')
				->max('id_pro'); //adquiere el id_pro de mayor valor

			$archivosUsados = DB::table('procesos')
				->where('id_pro', '=', $proceso) //se busca en especifico los datos del proceso
				->first();

			$buSAP = explode(",", $archivosUsados->id_es);
			$buCred = explode(",", $archivosUsados->id_ec);

			$busquedaSAP = "";
			$busquedaCred = "";

			foreach ($buSAP as $bs) {
				$busquedaSAP .= "id_es = " . $bs . " or ";
			}
			$busquedaSAP .= "a"; //se le asigna de la siguiente manera: id_es=$bs or a

			foreach ($buCred as $bc) {
				$busquedaCred .= "id_ec = " . $bc . " or ";
			}
			$busquedaCred .= "a";
			$checalo = $busquedaSAP;
			$busquedaSAP = substr(str_replace("or a", "", $busquedaSAP), 0, 12); //no captura realmente nada $busquedaSAP = substr(str_replace("or a", "", $busquedaCred), 0, -12)
			$busquedaCred = substr(str_replace("or a", "", $busquedaCred), 0, 12); //modificado 09/04/2021

			$covestro = DB::table("covestro")
				->first(); //obtiene lo unico que hay en esa tabla

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
				->where('id_pro', '=', $proceso)
				->get(); //obtine la fila donde se esta realizando el proceso con el id_pro como identificador

			$incidentes = DB::table("incidencias")
				->get();

			$facturas = DB::table("factura")
				->get();

			$parcialidades = DB::table("parcialidades")
				->get(); //obtine todas las parcialidades

			$total = 0;

			$ruta = "../ARCHIVOS/SALIDA_TXT";
			//mkdir($ruta);

			$files = glob('../ARCHIVOS/SALIDA_TXT/*'); //obtenemos todos los nombres de los ficheros
			foreach ($files as $file) {
				if (is_file($file))
					unlink($file); //elimino el fichero
			}

			foreach ($correctos as $cor) {
				DB::table("complemento")
					->where('id_comp', '=', $cor->id_comp)
					->update([
						"fecha_clearing" => date("Y-m-d H:i:s") //actualiza la fecha para el complemento con fecha y hora
					]);

				$total = $total + 1;
				$cliente = DB::table("clientes")
					->where('rfc_c', '=', $cor->rfc_c)
					->first();
				$encontro = false;
				$tipo = "";
				$pag = "";
				$formas = DB::table("formas_pago")
					->get();

				if ($cor->monedaP == "MXN") {
					$tipo = "";
				} else {
					$tipo = $cor->tipocambioP;
				}

				$pag = $request->get("FormatoDePagoP");

				$nombre_archivo = $ruta . "/" . $cor->rfc_c . "_" . $cor->clearing_document . ".txt"; //nombre del archivo
				if (strlen($cor->fechap) == 23) {
					$separacion = explode(" ", $cor->fechap);
					$date = explode("-", $separacion[0]);
					$date = $date[2] . "" . $date[1] . "" . $date[0];
					if ($separacion[1] == "00:00:00.000") {
						$time = "120000";
					} else {
						$time = str_replace(":", "", $separacion[1]);
						$time = str_replace(".000", "", $time);
					}
					$fecha = $date . $time;
				} elseif($request->get("fechap")) {
					$date = explode("-", $request->get("fechap"));
					$date = $date[2] . "" . $date[1] . "" . $date[0];
					$time = "120000";
					$fecha = $date . $time;
				} else {
					$fecha =  $cor->fechap;
				}
				if ($cor->residenciafiscal != null) { //si es nulo se crea el primer texto para el archivo txt
					$mensaje = "CABPAGOS|" . $covestro->version_complemento . "||" . $cor->clearing_document . "|" . date("dmYHis") . "|" . $cor->regimen . "|" . $covestro->rfc_e . "|" . $covestro->nombre_e . "|" . $covestro->calle_e . "|" . $covestro->numext_e . "|" . $covestro->numint_e . "|" . $covestro->colonia_e . "|" . $covestro->localidad_e . "||" . $covestro->municipio_e . "|" . $covestro->estado_e . "|" . $covestro->pais_e . "|" . $covestro->cpostal_e . "|" . $covestro->cpostal_e . "|XEXX010101000|" . $cor->nombre_c . "|||||||||||" . $cor->residenciafiscal . "|" . $cor->numregidtrib . "|\r\n";
				} else {
					$mensaje = "CABPAGOS|" . $covestro->version_complemento . "||" . $cor->clearing_document . "|" . date("dmYHis") . "|" . $cor->regimen . "|" . $covestro->rfc_e . "|" . $covestro->nombre_e . "|" . $covestro->calle_e . "|" . $covestro->numext_e . "|" . $covestro->numint_e . "|" . $covestro->colonia_e . "|" . $covestro->localidad_e . "||" . $covestro->municipio_e . "|" . $covestro->estado_e . "|" . $covestro->pais_e . "|" . $covestro->cpostal_e . "|" . $covestro->cpostal_e . "|" . $cor->rfc_c . "|" . $cor->nombre_c . "|||||||||||" . $cor->residenciafiscal . "|" . $cor->numregidtrib . "|\r\n";
				}

				foreach ($parcialidades as $f) { //no hace nada
					if ($f->clearing_document == $cor->clearing_document) {
						$dividirsalins = explode("/", $f->impsaldoins);
						if($dividirsalins[1] != 0 || $dividirsalins[1] != "0"){
							$mensaje.="CFDIREL|0" . $dividirsalins[1] . "|" . $f->folio . "\r\n";
						}
					}
				}

				if (strlen($cor->ctaord) > 18) {
					$cuentaord = "";
				} else {
					$cuentaord = $cor->ctaord;
				}

				$separarmonto =explode("/", $cor->montoP);
				$elmonto = explode(".", $separarmonto[0]);
				$montodec = 0;
				$pagado = 0;
				$insoluto = 0;

				switch ($decimal[1]) {
					case 2:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.00';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.00';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 3:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 4:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.0000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.0000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 5:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.00000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.00000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 6:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.000000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.000000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec  = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 5) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 7:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.0000000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.0000000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 5) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 6) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 8:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.00000000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.00000000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 5) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 6) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 7) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 9:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.000000000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.000000000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000000';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000';
							} elseif(strlen($elmonto[1]) == 5) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 6) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 7) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 8) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					case 10:
						if(sizeof($elmonto) == 1) { // anterior
							$montodec = $elmonto[0] . '.0000000000';
						} else {
							if(strlen($elmonto[1]) == 0) {
								$montodec = $elmonto[0] . '.0000000000';
							} elseif(strlen($elmonto[1]) == 1) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000000000';
							} elseif(strlen($elmonto[1]) == 2) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000000';
							} elseif(strlen($elmonto[1]) == 3) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000000';
							} elseif(strlen($elmonto[1]) == 4) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000000';
							} elseif(strlen($elmonto[1]) == 5) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00000';
							} elseif(strlen($elmonto[1]) == 6) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0000';
							} elseif(strlen($elmonto[1]) == 7) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'000';
							} elseif(strlen($elmonto[1]) == 8) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'00';
							} elseif(strlen($elmonto[1]) == 9) {
								$montodec = $elmonto[0] . '.' . $elmonto[1] .'0';
							} else {
								$montodec = $separarmonto[0];
							}
						}
						break;
					default :
						$montodec = $separarmonto[0];
					break;
				}

				if ($cor->rfcctaben == "XEXX010101000") {
					if (strlen($cor->cataben) < 10 && $cor->cataben != "" && !is_null($cor->cataben)) {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|0" . $cor->cataben . "\r\n";
					} else {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|" . $cor->cataben . "\r\n";
					}
				} else {
					if (strlen($cor->cataben) < 10 && $cor->cataben != "" && !is_null($cor->cataben)) {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|0" . $cor->cataben . "\r\n";
					} else {
						$mensaje .= "CPAGO|" . $covestro->numpago . "|" . $fecha . "|" . $pag . "|" . $cor->monedaP . "|" . $tipo . "|" . $montodec . "|" . $cor->numeroperP . "|" . $cor->rfcctaord . "|" . $cor->bancoordext . "|" . $cuentaord . "|" . $cor->rfcctaben . "|" . $cor->cataben . "\r\n";
					}
				}

				if ($covestro->usar_credito == 1) {
					$parci = DB::table("credito")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(' . $busquedaCred . ')'))
						->get();
					foreach ($parci as $par) {
						if ($par->moneda == $cor->monedaP) {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						} else {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						}
					}
					$parci = DB::table("facturas_liquidadas")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(id_es=' . str_replace(",", "", $archivosUsados->id_es) . ')'))
						->get();
					//aqui se le asigna al txt el tipo de cambio
					foreach ($parci as $par) {
						if ($par->moneda == $cor->monedaP) {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						} else {
							$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $par->impsaldoant . "|" . $par->imppagado . "|" . $par->impsaldoins . "\r\n";
						}
					}
				} else {
					$parci = DB::table("parcialidades")
						->where("clearing_document", "=", $cor->clearing_document)
						->whereRaw(DB::raw('(id_es=' . str_replace(",", "", $archivosUsados->id_es) . ')'))
						->get();
					foreach ($parci as $par) {
						$impanterior = explode(".", $par->impsaldoant);
						$impagado = explode(".", $par->imppagado);
						$dividirsalins = explode("/", $par->impsaldoins);
						$impinsoluto = explode(".", $dividirsalins[0]);
						$anterior = 0;
						$pagado = 0;
						$insoluto = 0;

						switch ($decimal[1]) {
							case 2:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.00';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.00';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.00';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.00';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.00';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.00';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 3:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 4:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.0000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.0000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.0000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.0000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.0000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.0000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 5:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.00000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.00000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.00000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.00000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.00000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.00000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 6:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.000000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.000000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 5) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.000000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.000000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 5) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.000000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.000000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 5) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 7:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.0000000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.0000000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 5) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 6) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.0000000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.0000000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 5) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 6) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.0000000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.0000000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 5) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 6) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 8:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.00000000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.00000000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 5) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 6) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 7) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.00000000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.00000000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 5) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 6) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 7) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.00000000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.00000000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 5) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 6) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 7) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 9:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.000000000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.000000000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000000';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000';
									} elseif(strlen($impanterior[1]) == 5) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 6) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 7) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 8) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.000000000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.000000000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000000';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000';
									} elseif(strlen($impagado[1]) == 5) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 6) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 7) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 8) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.000000000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.000000000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000000';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000';
									} elseif(strlen($impinsoluto[1]) == 5) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 6) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 7) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 8) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							case 10:
								if(sizeof($impanterior) == 1) { // anterior
									$anterior = $impanterior[0] . '.0000000000';
								} else {
									if(strlen($impanterior[1]) == 0) {
										$anterior = $impanterior[0] . '.0000000000';
									} elseif(strlen($impanterior[1]) == 1) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000000000';
									} elseif(strlen($impanterior[1]) == 2) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000000';
									} elseif(strlen($impanterior[1]) == 3) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000000';
									} elseif(strlen($impanterior[1]) == 4) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000000';
									} elseif(strlen($impanterior[1]) == 5) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00000';
									} elseif(strlen($impanterior[1]) == 6) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0000';
									} elseif(strlen($impanterior[1]) == 7) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'000';
									} elseif(strlen($impanterior[1]) == 8) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'00';
									} elseif(strlen($impanterior[1]) == 9) {
										$anterior = $impanterior[0] . '.' . $impanterior[1] .'0';
									} else {
										$anterior = $par->impsaldoant;
									}
								}
								if(sizeof($impagado) == 1) { // pagado
									$pagado = $impagado[0] . '.0000000000';
								} else {
									if(strlen($impagado[1]) == 0) { // pagado
										$pagado = $impagado[0] . '.0000000000';
									} elseif(strlen($impagado[1]) == 1) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000000000';
									} elseif(strlen($impagado[1]) == 2) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000000';
									} elseif(strlen($impagado[1]) == 3) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000000';
									} elseif(strlen($impagado[1]) == 4) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000000';
									} elseif(strlen($impagado[1]) == 5) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00000';
									} elseif(strlen($impagado[1]) == 6) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0000';
									} elseif(strlen($impagado[1]) == 7) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'000';
									} elseif(strlen($impagado[1]) == 8) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'00';
									} elseif(strlen($impagado[1]) == 9) {
										$pagado = $impagado[0] . '.' . $impagado[1] .'0';
									} else {
										$pagado = $par->imppagado;
									}
								}
								if(sizeof($impinsoluto) == 1) { // insoluto
									$insoluto = $impinsoluto[0] . '.0000000000';
								} else {
									if(strlen($impinsoluto[1]) == 0) { // insoluto
										$insoluto = $impinsoluto[0] . '.' . '.0000000000';
									} elseif(strlen($impinsoluto[1]) == 1) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000000000';
									} elseif(strlen($impinsoluto[1]) == 2) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000000';
									} elseif(strlen($impinsoluto[1]) == 3) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000000';
									} elseif(strlen($impinsoluto[1]) == 4) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000000';
									} elseif(strlen($impinsoluto[1]) == 5) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00000';
									} elseif(strlen($impinsoluto[1]) == 6) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0000';
									} elseif(strlen($impinsoluto[1]) == 7) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'000';
									} elseif(strlen($impinsoluto[1]) == 8) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'00';
									} elseif(strlen($impinsoluto[1]) == 9) {
										$insoluto = $impinsoluto[0] . '.' . $impinsoluto[1] .'0';
									} else {
										$insoluto = $par->impsaldoins;
									}
								}
								break;
							default :
								$anterior = $par->impsaldoant;
							break;
						}
							
							
							if ($par->moneda == $cor->monedaP) {
								$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "||" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $anterior . "|" . $pagado . "|" . $insoluto . "\r\n";
							} else {
								$mensaje .= "CPAGODR|1|" . $par->folio . "||" . $par->folio . "|" . $par->moneda . "|" . $par->tipcambio . "|" . $request->get("MetodoDePagoDR") . "|" . $par->numparcialidad . "|" . $anterior . "|" . $pagado . "|" . $insoluto . "\r\n";
							}
						}
					}
					
				if ($archivo = fopen($nombre_archivo, "a")) { //crea el nuevo archivo txt
					if (fwrite($archivo, $mensaje)) { //escribe en el archivo txt
						$hola = true;
					} else {
						$hola = false;
					}
					fclose($archivo);
				}
				$otra_fecha = explode("-", $cor->fechabus);
				
				$archivo = new Archivos;
				$archivo->nombre = $cor->rfc_c . "_" . $cor->clearing_document . ".txt";
				$archivo->fecha = date('Y-m-d');
				$archivo->fechabus = $otra_fecha[0] . "-" . $otra_fecha[2] . "-" . $otra_fecha[1];
				$archivo->ruta = "../ARCHIVOS/PROCESADOS_TXT/";
				$archivo->timbrado = "0";
				$archivo->clearing = $cor->clearing_document;
				$archivo->id_cliente = $cor->id_cliente;
				$archivo->rfc_cliente = $cor->rfc_c;
				$archivo->cliente = $cor->nombre_c;
				$archivo->id_pro = $cor->id_pro;
				$archivo->generapdf = $cor->rfc_c . "_" . $cor->clearing_document . ".pdf";
				$archivo->generaxml = $cor->rfc_c . "_" . $cor->clearing_document . ".xml";
				$archivo->save();
			}
			$cuantos = DB::table("complemento")
				->count();
			DB::table("procesos")
				->where("id_pro", "=", $proceso)
				->update([
					"total" => $total,
					"integracion" => 0,
					"timbrado" => 1,
					"obtencion" => 0
				]); // se guarda la actualizacion cambia el parametro timbrado a uno
			if ($cuantos == 0) {
				$larespuest = "2";
			} else {
				$larespuest = "si";
			}
			$quepasa = "todo chido";
		} catch (\Exception $th) {
			$quepasa = $th->getMessage();
			$larespuest = "0";
			$proceso = DB::table('procesos')
				->max('id_pro'); //adquiere el id_pro de mayor valor

			$archivosUsados = DB::table('procesos')
				->where('id_pro', '=', $proceso) //se busca en especifico los datos del proceso
				->first();

			$buSAP = explode(",", $archivosUsados->id_es);
			$buCred = explode(",", $archivosUsados->id_ec);

			$busquedaSAP = "";
			$busquedaCred = "";

			foreach ($buSAP as $bs) {
				$busquedaSAP .= "id_es = " . $bs . " or ";
			}
			$busquedaSAP .= "a"; //se le asigna de la siguiente manera: id_es=$bs or a

			foreach ($buCred as $bc) {
				$busquedaCred .= "id_ec = " . $bc . " or ";
			}
			$busquedaCred .= "a";

			$busquedaSAP = substr(str_replace("or a", "", $busquedaSAP), 0, 12); //no captura realmente nada $busquedaSAP = substr(str_replace("or a", "", $busquedaCred), 0, -12)
			$busquedaCred = substr(str_replace("or a", "", $busquedaCred), 0, 12); //modificado 09/04/2021
			$checalo = str_replace(",", "", $archivosUsados->id_es);
		}
		return response()->json([
			"respuesta" => $larespuest,
			"mensaje" => $quepasa,
			"otrmens" => $checalo
		]);
	}
}
