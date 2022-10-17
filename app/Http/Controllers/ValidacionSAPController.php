<?php

namespace App\Http\Controllers;

use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
use App\Liquidadas_Model;
use App\Bancos_SAP_Model;
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
use Illuminate\Mail\Message;

class ValidacionSAPController extends Controller
{
	public function index()
	{
		$layouts = DB::table('bancos_l_SAP')
			->get();

		DB::table("temporal_SAP")
			->where("usuario", "=", Session::get("user"))
			->delete();

		DB::table("excel_sap")
			->where("integrado", "=", 3)
			->where("correo", "=", Session::get("user"))
			->delete();

		return view('SAT.validacionSAT', ["layout" => $layouts]);
	}

	public function guardarPrueba(Request $request)
	{
		$email = session()->get('user');

		DB::table("excel_sap")
			->where('integrado', 3)
			->where('correo', $email)
			->delete();

		try {
			$num_archivo = 0;
			$nom_archivo = "";

			$elimina = DB::table("temporal_SAP")
				->delete();

			Session::put('layout', $request->get('layout'));

			$lay = DB::table('bancos_l_SAP')
				->where('id_ls', '=', Session::get('layout'))
				->first();


			$hojaSap = $lay->hoja_sap;
			$hojaBancos = $lay->hoja_bancos;

			foreach ($request->excel as $archivo) {
				$num_archivo = $num_archivo + 1;
				Session::put('nombre_archivo_sap', $archivo->getClientOriginalName());
				$id_ar = DB::table('excel_SAP')->insertGetId(
					['nombre' => Session::get('nombre_archivo_sap'), 'fecha' => date("Y-m-d"), 'integrado' => 3, "id_pro" => 0, "correo" => $email]
				);
				Session::put('num_archivo', $id_ar);
				Excel::selectSheets($hojaSap)->load($archivo, function ($reader) use($email) {
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
					$MONTOPAGOMXN = $lay->MONTOPAGOMXN;
					$TIPOCAMBIOP = $lay->TIPOCAMBIOP;
					$TIPODOC = $lay->TIPODOC;
					$FECHAPAGO = $lay->FECHAPAGO;
					$FOLIOS = $lay->FOLIOS;
					$PARCIAL = $lay->PARCIAL;
					$ASSIGNMENT = $lay->ASSIGNMENT;
					$REFERENCE = $lay->REFERENCE;
					$NUMREGIDTRIB = $lay->NUMREGIDTRIB;
					$TAX =  $lay->IMPUESTO;


					$covestro = DB::table("covestro")
						->first();
					foreach ($reader->get() as $key => $row) {
						if ($row[$ID] != "") {
							$sap = new SAP_Pruebas_Model;
							$sap->id_cliente = $row[$ID];
							$id = $row[$ID];
							$existe = DB::table("clientes")
								->where("id_cliente", '=', $id)
								->count();

							if ($existe >= 1) {
								$cliente = DB::table("clientes")
									->where("id_cliente", '=', $id)
									->first();

								if ($cliente->residenciafiscal != "MX") {
									$sap->RFC_R = "XEXX010101000";
									$sap->NOMBRE_R = $cliente->nombre_c;
									$sap->DIRECCION_R = $cliente->direccion_c;
									$residencias = DB::table("residencia")
										->get();

									foreach ($residencias as $resi) {
										if ($resi->equivalencia == $cliente->residenciafiscal) {
											$sap->RESIDENCIAFISCAL = $resi->resid;
										}
									}

									$sap->NUMREGIDTRIB = $row[$NUMREGIDTRIB];
								} else {
									$sap->RFC_R = $cliente->rfc_c;
									$sap->NOMBRE_R = $cliente->nombre_c;
									$sap->DIRECCION_R = $cliente->direccion_c;
									$sap->RESIDENCIAFISCAL = "";
									$sap->NUMREGIDTRIB = "";
								}
							} else {
								$sap->RFC_R = "El cliente con id " . $id . " no existe";
								$sap->NOMBRE_R = "El cliente con id " . $id . " no existe";
								$sap->DIRECCION_R = "El cliente con id " . $id . " no existe";
								$sap->RESIDENCIAFISCAL = "El cliente con id " . $id . " no existe";
								$sap->NUMREGIDTRIB = "El cliente con id " . $id . " no existe";
							}
							$sap->REGIMEN = $covestro->regimen;
							$sap->RFC_E = $covestro->rfc_e;
							$sap->NOMBRE_E = $covestro->nombre_e;
							$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
							$sap->NUMPAGO = $covestro->numpago;

							$sap->LUGAREXPEDICION = $covestro->cpostal_e;
							$sap->FOLIO = date("Y") . $row[$FOLIO];
							$sap->MONEDAPAGO = $row[$MONEDAPAGO];
							$sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
							$sap->MONTOPAGOMXN = str_replace("-", "", $row[$MONTOPAGOMXN]);
							$sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
							$sap->TIPODOC = $row[$TIPODOC];
							$sap->FECHADOC = $row[$FECHAPAGO];
							$sap->ASSIGNMENT = $row[$ASSIGNMENT];
							$sap->REFERENCE = $row[$REFERENCE];
							$sap->TAX = $row[$TAX];
							$sap->FOLIOS = substr($row[$FOLIOS], -7);
							$sap->ID_DOC = substr($row[$FOLIOS], -7);
							$sap->PARCIAL = $row[$PARCIAL];
							$sap->CADENAP = Session::get('num_archivo');
							$sap->SELLOP = Session::get('nombre_archivo_sap');
							$sap->usuario = $email;
							$sap->save();
						} else {
							$sap = new SAP_Pruebas_Model;
							$sap->RFC_R = "Este pago/factura no tiene id del cliente";
							$sap->NOMBRE_R = "Este pago/factura no tiene id del cliente";
							$sap->DIRECCION_R = "Este pago/factura no tiene id del cliente";
							$sap->RESIDENCIAFISCAL = "Este pago/factura no tiene id del cliente";
							$sap->NUMREGIDTRIB = "Este pago/factura no tiene id del cliente";
							$sap->REGIMEN = $covestro->regimen;
							$sap->RFC_E = $covestro->rfc_e;
							$sap->NOMBRE_E = $covestro->nombre_e;
							$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
							$sap->NUMPAGO = $covestro->numpago;
							$sap->LUGAREXPEDICION = $covestro->cpostal_e;
							$sap->FOLIO = (int)$row[$FOLIO];
							$sap->MONEDAPAGO = $row[$MONEDAPAGO];
							$sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
							$sap->MONTOPAGOMXN = str_replace("-", "", $row[$MONTOPAGOMXN]);
							$sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
							$sap->TIPODOC = $row[$TIPODOC];
							$sap->FECHADOC = $row[$FECHAPAGO];
							$sap->FOLIOS = substr($row[$FOLIOS], -7);
							$sap->ID_DOC = substr($row[$FOLIOS], -7);
							$sap->PARCIAL = $row[$PARCIAL];
							$sap->CADENAP = Session::get('num_archivo');
							$sap->SELLOP = Session::get('nombre_archivo_sap');
							$sap->usuario = $email;
							$sap->save();
						}
					}
				});

				Excel::selectSheets($hojaBancos)->load($archivo, function ($reader) {
					$vieneCliente = false;
					$primero = 0;
					$ultimo = 0;

					$lay = DB::table('bancos_l_SAP')
						->where('id_ls', '=', Session::get('layout'))
						->first();

					$FOLIO = $lay->FOLIO;
					$MONTOPAGO = $lay->MONTOPAGO;
					$MONTOPAGOMXN = $lay->MONTOPAGOMXN;
					$MONEDAPAGO = $lay->MONEDAPAGO;
					$TIPOCAMBIOP = $lay->TIPOCAMBIOP;

					$covestro = DB::table("covestro")
						->first();
					foreach ($reader->get() as $key => $row) {
						$sap = new Bancos_SAP_Model;
						$resultado1 = strlen($row[$FOLIO]);
						switch ($resultado1) {
							case 8:
								$valor1 = substr($row[$FOLIO], -3);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 9:
								$valor1 = substr($row[$FOLIO], -4);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 10:
								$valor1 = substr($row[$FOLIO], -5);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 11:
								$valor1 = substr($row[$FOLIO], -6);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 12:
								$valor1 = substr($row[$FOLIO], -7);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 13:
								$valor1 = substr($row[$FOLIO], -8);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 14:
								$valor1 = substr($row[$FOLIO], -9);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 15:
								$valor1 = substr($row[$FOLIO], -10);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 16:
								$valor1 = substr($row[$FOLIO], -11);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							case 17:
								$valor1 = substr($row[$FOLIO], -12);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
							default:
								$valor1 = substr($row[$FOLIO], 5);
								$sap->clearing_document = (int)date("Y") . $valor1;
								break;
						}
						$sap->monto = str_replace("-", "", $row[$MONTOPAGO]);
						$sap->montomxn = str_replace("-", "", $row[$MONTOPAGOMXN]);
						$sap->monedaP = $row[$MONEDAPAGO];
						$sap->tipocambioP = str_replace("-", "", $row[$TIPOCAMBIOP]);
						$sap->usuario = Session::get('user');
						$sap->save();
					}
				});
			}

			$mostrar = DB::table('temporal_SAP')
				->where('usuario', '=', Session::get('user'))
				->get();

			return response()->json($mostrar);
		} catch (\Exception $e) {
			return response()->json([
				"respuesta" => 2,
				"mensaje" => $e->getMessage(),
				"archivo" => Session::get('nombre_archivo_sap')
			]);
		}
	}

	public function guardarDatos(Request $request)
	{
		//-------------variables a utilizar para comprobar las acciones sql server
		$elusuario = Session::get('user');
		$unoactual = "";
		$dosactual = "";
		$tresactual = "";
		$cuatroactual = "";
		$cincoactual = "";
		$seisactual = "";
		$sieteactual = "";
		$ochoactual = "";
		$mostrar = "";
		$ques = "";
		//-----------------
		try {
			$clearing = "";
			$fact = "";
			$acum = 0;
			$acum2 = 0;
			$hoja2cambio = "";
			$hoja2moneda = "";
			$dz = false;
			$prueba = DB::table('temporal_SAP')
				->where('usuario', '=', Session::get('user'))
				->get();

			foreach ($prueba as $p) {
				$hoja2cambio = "";
				$hoja2moneda = "";
				$monedita = "";
				$dividir = explode(".", $p->FECHADOC);
				if ($p->TIPODOC == "DZ") {
					if ($p->FOLIOS == 0 || $p->FOLIOS == "" || is_null($p->FOLIOS) || $p->FOLIOS == "0" || $p->FOLIOS == "#") {
						$dz = true;
						$monedita = $p->MONEDAPAGO;
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
						$pago->fechabus = $dividir[2] . "-" . $dividir[1] . "-" . $dividir[0] . " 12:00:00";
						$pago->assignment = $p->ASSIGNMENT;
						$pago->reference = $p->REFERENCE;
						$pago->tipocambioP = $p->TIPOCAMBIOP;
						if ($p->MONEDAPAGO != "MXN") {
							$monto = str_replace(" ", "", $p->MONTOPAGO);
						} else {
							$monto = str_replace(" ", "", $p->MONTOPAGOMXN);
						}
						$monto = str_replace("$", "", $monto);
						$monto = str_replace(",", "", $monto);
						$monto = str_replace("MXN", "", $monto);
						$monto = str_replace("mxn", "", $monto);
						if ($monto < 0) {
							$pago->signo = "-";
						} else {
							$pago->signo = "+";
						}
						$monto = str_replace("-", "", $monto);
						if ($p->MONEDAPAGO != "MXN") {
							$pago->montoP = (float)$monto - $acum;
						} else {
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
						$pago->id_es = $p->CADENAP;
						$pago->save();

						$acum = 0;
						$acum2 = 0;
						$clearing = $p->FOLIO;
						$precio = "";

						/*$hoja2 = DB::table("bancos_sap")
						//->where("usuario", "=", Session::get("usuario"))
						->where("clearing_document", "=", $p->FOLIO)
						->first();

					$cosa = $hoja2->clearing_document;*/

						$hoja2 = DB::table("bancos_sap")
							//->where("usuario", "=", Session::get("usuario"))
							->where("clearing_document", "=", $p->FOLIO)
							->count();

						if ($hoja2 > 0) {
							$hoja2 = DB::table("bancos_sap")
								//->where("usuario", "=", Session::get("usuario"))
								->where("clearing_document", "=", $p->FOLIO)
								->first();

							$hoja2cambio = $hoja2->tipocambioP;
							$hoja2moneda = $hoja2->monedaP;
						}

						$cr2 = DB::table("credito")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$fl2 = DB::table("facturas_liquidadas")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$par2 = DB::table("parcialidades")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						foreach ($cr2 as $c) {
							if ($hoja2moneda != "" && $c->moneda != $monedita) {
								try {
									$unoactual = DB::table("credito")
										->where("id_cre", "=", $c->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $df) {
									$unoactual = $df->getMessage();
								}
							}
						}

						foreach ($fl2 as $f) {
							if ($hoja2moneda != "" && $f->moneda != $monedita) {
								try {
									$dosactual = DB::table("facturas_liquidadas")
										->where("id_cre", "=", $f->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $bf) {
									$dosactual = $bf->getMessage();
								}
							}
						}

						foreach ($par2 as $p) {
							if ($hoja2moneda != "" && $p->moneda != $monedita) {
								try {
									$tresactual = DB::table("parcialidades")
										->where("id_par", "=", $p->id_par)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $rt) {
									$tresactual = $rt->getMessage();
								}
							}
						}
					} else {
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
				} elseif ($p->TIPODOC == "DC") {
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
					$pago->fechabus = $dividir[2] . "-" . $dividir[1] . "-" . $dividir[0] . " 12:00:00";
					$pago->assignment = $p->ASSIGNMENT;
					$pago->reference = $p->REFERENCE;
					$pago->tipocambioP = $p->TIPOCAMBIOP;
					$monto = str_replace(" ", "", $p->MONTOPAGO);
					$monto = str_replace("$", "", $monto);
					$monto = str_replace(",", "", $monto);
					$monto = str_replace("MXN", "", $monto);
					$monto = str_replace("mxn", "", $monto);
					if ($monto < 0) {
						$pago->signo = "-";
					} else {
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
					$pago->id_cliente = $p->id_cliente;
					$pago->rfc_e = $p->RFC_E;
					$pago->nombre_e = $p->NOMBRE_E;
					$pago->timbrado = "0";
					$pago->id_es = $p->CADENAP;
					$pago->save();
				} elseif ($p->TIPODOC ==  "AB") {
					$hay = DB::table("parcialidades")
						->where("folio", "=", $p->FOLIOS)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($hay < 1) {
						$parti = explode(":", $p->PARCIAL);
						if (count($parti) == 3) {
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
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							if ($p->tax == "A3" || $p->tax == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();
							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							$fechita = explode(".", $p->FECHADOC);
						} else {
							$numpar = str_split($p->PARCIAL);
							if ($p->MONEDAPAGO != "MXN") {
								$precio = str_replace("$", "", $p->MONTOPAGO);
							} else {
								$precio = str_replace("$", "", $p->MONTOPAGOMXN);
							}
							$parcial = new Parcialidades;
							$parcial->tipcambio = $p->TIPOCAMBIOP;
							$parcial->moneda = $p->MONEDAPAGO;
							if (is_numeric($numpar[0])) {
								$parcial->numparcialidad = $numpar[0];
							} else {
								$parcial->numparcialidad = "1";
							}
							$parcial->impsaldoant = $precio;
							$parcial->imppagado = $precio;
							$parcial->impsaldoins = "0";
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							if ($p->tax == "A3" || $p->tax == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();

							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							$fechita = explode(".", $p->FECHADOC);
						}
					}
				} elseif ($p->TIPODOC == "RV") {
					$existe = DB::table('factura')
						->where('folio', '=', $p->FOLIOS)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					$fechita = explode(".", $p->FECHADOC);
					if ($existe < 1) {
						$factura = new Facturas;
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$factura = new Facturas;
						$precio = str_replace("$", "", $p->MONTOPAGO);
						$factura->folio = $p->FOLIOS;
						$factura->monto = $precio;
						$factura->moneda = $p->MONEDAPAGO;
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();
								$factura->tipo_cambio =  $tipo_de_cambio->tipocambioP;
								$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
								if ($p->tax == "A3" || $p->tax == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) - (((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $tipo_de_cambio->tipocambioP;
								}
							} else {
								$factura->tipo_cambio =  $p->TIPOCAMBIOP;
								$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
								if ($p->tax == "A3" || $p->tax == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) - (((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $p->TIPOCAMBIOP;
								}
							}
						} else {
							$factura->tipo_cambio =  "1.00";
							$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
							if ($p->tax == "A3" || $p->tax == "A9") {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "16";
								$factura->impuesto = (float)$precio - (((float)$precio) / 1.16);
								$factura->sin_impuesto = ((float)$precio) / 1.16;
							} else {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "0";
								$factura->impuesto = "0";
								$factura->sin_impuesto = $precio;
							}
						}
						$factura->id_cliente = $p->id_cliente;
						$factura->nombre_c = $p->NOMBRE_R;
						$factura->residencia = $p->RESIDENCIAFISCAL;
						$factura->clearings = $p->FOLIO;
						$factura->save();
					} else {
						$varios = DB::table("factura")
							->where("folio", "=", $p->FOLIOS)
							->first();

						try {
							$cuatroactual = DB::table("factura")
								->where("folio", "=", $p->FOLIOS)
								->update([
									"clearings" => $varios->clearings . ", " . $p->FOLIO
								]);
						} catch (\Exception $nh) {
							$cuatroactual = $nh->getMessage();
						}
					}
					$existe = DB::table('parcialidades')
						->where('folio', '=', $p->FOLIOS)
						->where('clearing_document', '=', $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($existe < 1) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$parcial->impsaldoins = "0";
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($p->tax == "A3" || $p->tax == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();

						$parcia = new Liquidadas_Model;
						$parcia->tipcambio = $p->TIPOCAMBIOP;
						$parcia->moneda = $p->MONEDAPAGO;
						$parcia->numparcialidad = "1";
						$parcia->impsaldoant = $precio;
						$parcia->imppagado = $precio;
						$parcia->impsaldoins = "0";
						$parcia->folio = $p->FOLIOS;
						$parcia->clearing_document = $p->FOLIO;
						$parcia->id_es = $p->CADENAP;
						$parcia->save();
					} elseif ($existe % 2 == 0) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$parcial->impsaldoins = "0";
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($p->tax == "A3" || $p->tax == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();
					}
				} elseif ($p->TIPODOC == "RW") {
					if ($p->MONEDAPAGO != "MXN") {
						$precio = str_replace("$", "", $p->MONTOPAGO);
					} else {
						$precio = str_replace("$", "", $p->MONTOPAGOMXN);
					}
					$assig = substr($p->ASSIGNMENT, 13);
					$encuentra = DB::table('parcialidades')
						->where("imppagado", "=", $precio)
						->where("folio", "=", $assig)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();

					if ($encuentra == 1) {
						$pre = DB::table('parcialidades')
							->where("imppagado", "=", $precio)
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->first();

						$encuentra = DB::table('parcialidades')
							->where("id_par", "=", $pre->id_par)
							->delete();
					} else {
						$encuentra = DB::table('parcialidades')
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->count();

						if ($encuentra == 1) {
							$pre = DB::table('parcialidades')
								->where("folio", "=", $assig)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->first();
							try {
								$cincoactual = DB::table('parcialidades')
									->where('id_par', '=', $pre->id_par)
									->update([
										"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
										"imppagado" => (float)$pre->imppagado - (float)$precio,
									]);
							} catch (\Exception $ft) {
								$cincoactual = $ft->getMessage();
							}
						} else {
							$encuentra = DB::table('parcialidades')
								->where("imppagado", "=", $precio)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->count();

							if ($encuentra >= 1) {
								$pre = DB::table('parcialidades')
									->where("imppagado", "=", $precio)
									->where("clearing_document", "=", $p->FOLIO)
									->where('id_es', '=', $p->CADENAP)
									->orderBy('id_par', 'desc')
									->first();

								$encuentra = DB::table('parcialidades')
									->where("id_par", "=", $pre->id_par)
									->delete();
							} else {
								$encuentra = DB::table('parcialidades')
									->where("clearing_document", '=', $p->FOLIO)
									->max("imppagado");

								if ($encuentra >= 1) {
									$pre = DB::table('parcialidades')
										->where("clearing_document", "=", $p->FOLIO)
										->where("imppagado", "=", $encuentra)
										->where('id_es', '=', $p->CADENAP)
										->first();
									try {
										$seisactual = DB::table('parcialidades')
											->where('id_par', '=', $pre->id_par)
											->update([
												"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
												"imppagado" => (float)$pre->imppagado - (float)$precio,
											]);
									} catch (\Exception $nop) {
										$seisactual = $nop->getMessage();
									}
								}
							}
						}
					}
				}
			}
			try {
				$sieteactual = DB::table("bancos_sap")
					->where("id_es", "=", null)
					->update([
						"id_es" => Session::get("num_archivo")
					]);
			} catch (\Exception $gh) {
				$sieteactual = $gh->getMessage();
			}

			try {
				$ochoactual = DB::table("excel_SAP")
					->where("integrado", "=", 3)
					->update([
						"integrado" => 0
					]);
			} catch (\Exception $jk) {
				$ochoactual = $jk->getMessage();
			}
			try {
				$mostrar = DB::table('temporal_SAP')
					->where('usuario', '=', Session::get('user'))
					->count();
			} catch (\Exception $clo) {
				$mostrar = $clo->getMessage();
			}

			if ($mostrar > 0) {
				try {
					$mostrar2 = DB::table('temporal_SAP')
						->where('usuario', '=', Session::get('user'))
						->delete();
				} catch (\Exception $th) {
					$mostrar2 = $th->getMessage();
				}

				return response()->json([
					"respuesta" => "0",
					"sihay" => $mostrar,
					"elimina1" => $mostrar2, //si elimino los datos de la tabla temporal_Sap
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "todo chido"
				]);
			} else {
				try {
					$ques = DB::table("excel_SAP")
						->where("integrado", "=", 3)
						->delete();
				} catch (\Exception $xr) {
					$ques = $xr->getMessage();
				}

				return response()->json([
					"respuesta" => "2",
					"sihay" => $mostrar,
					"quees" => $ques,
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "no hay datos cargados"
				]);
			}
		} catch (\Exception $haber) {
			$mensaje = $haber->getMessage();
			return response()->json([
				"respuesta" => "2",
				"sihay" => $mostrar,
				"quees" => $ques,
				"unoup" => $unoactual, //actaliza tabla creditlocaldr
				"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
				"tresup" => $tresactual, //actaliza tabla parcialidades
				"cuatroup" => $cuatroactual, //actaliza tabla factura
				"cincoup" => $cincoactual, //actaliza tabla parcialidades
				"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
				"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
				"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
				"user" => $elusuario, // obtiene el correo de quien se logueo
				"mensajito" => $mensaje
			]);
		}
	}

	public function borrarPrueba(Request $request)
	{
		$mostrar = DB::table('temporal_SAP')
			->where('usuario', '=', Session::get('user'))
			->delete();

		$mostrar = DB::table('bancos_SAP')
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

		$mostrar = DB::table('excel_SAP')
			->where('integrado', '=', 3)
			->where("correo", "=", Session::get("user"))
			->delete();

		return response()->json([
			"respuesta" => "si"
		]);
	}

	public function guardarPrueba2(Request $request)
	{
		DB::table("excel_sap")
			->where("integrado", "=", 3)
			->where("correo", "=", Session::get("user"))
			->delete();

		try {
			$num_archivo = 0;
			$nom_archivo = "";

			$elimina = DB::table("temporal_SAP")
				->delete();

			//Session::put('layout', $request->get('layout'));

			$hojaSap = 'Hoja1';
			$hojaBancos = 'Hoja2';

			foreach ($request->excel as $archivo) {
				$num_archivo = $num_archivo + 1;
				try {
					Session::put('nombre_archivo_sap', $archivo->getClientOriginalName());
					$id_ar = DB::table('excel_SAP')->insertGetId(
						['nombre' => Session::get('nombre_archivo_sap'), 'fecha' => date("Y-m-d"), 'integrado' => 3, "id_pro" => 0, "correo" => Session::get('user')]
					);
					Session::put('num_archivo', $id_ar);
				}catch (\Exception $e) {
					return response()->json([
						"respuesta" => 2,
						"mensaje" => $e->getMessage(),
						"archivo" => Session::get('nombre_archivo_sap'),
						"usuario" => Session::get('user'),
						"num_archivo" => Session::get('num_archivo'),
						"seccion" => "Crear el número de archivo"
					]);
				}
				
				try {					
					Excel::selectSheets($hojaSap)->load($archivo, function ($reader) {
						$vieneCliente = false;
						$primero = 0;
						$ultimo = 0;

						$ID = 'id';
						$FOLIO = 'clearing_folio';
						$MONEDAPAGO = 'moneda';
						$MONTOPAGO = 'monto';
						$MONTOPAGOMXN = 'montomxn';
						$TIPOCAMBIOP = 'cambio';
						$TIPODOC = 'dtipo';
						$FECHAPAGO = 'fecha';
						$FOLIOS = 'foliofactura';
						$PARCIAL = 'parcialidades';
						$ASSIGNMENT = 'assignment';
						$REFERENCE = 'reference';
						$NUMREGIDTRIB = 'numregidtrib';
						$TAX =  'tax';


						$covestro = DB::table("covestro")
							->first();
						foreach ($reader->get() as $key => $row) {
							if ($row[$ID] != "") {
								$sap = new SAP_Pruebas_Model;
								$sap->id_cliente = $row[$ID];
								$id = $row[$ID];
								$existe = DB::table("clientes")
									->where("id_cliente", '=', $id)
									->count();

								if ($existe >= 1) {
									$cliente = DB::table("clientes")
										->where("id_cliente", '=', $id)
										->first();

									if ($cliente->residenciafiscal != "MX") {
										$sap->RFC_R = "XEXX010101000";
										$sap->NOMBRE_R = $cliente->nombre_c;
										$sap->DIRECCION_R = $cliente->direccion_c;
										$residencias = DB::table("residencia")
											->get();

										foreach ($residencias as $resi) {
											if ($resi->equivalencia == $cliente->residenciafiscal) {
												$sap->RESIDENCIAFISCAL = $resi->resid;
											}
										}

										$sap->NUMREGIDTRIB = $row[$NUMREGIDTRIB];
									} else {
										$sap->RFC_R = $cliente->rfc_c;
										$sap->NOMBRE_R = $cliente->nombre_c;
										$sap->DIRECCION_R = $cliente->direccion_c;
										$sap->RESIDENCIAFISCAL = "";
										$sap->NUMREGIDTRIB = "";
									}
								} else {
									$sap->RFC_R = "El cliente con id " . $id . " no existe";
									$sap->NOMBRE_R = "El cliente con id " . $id . " no existe";
									$sap->DIRECCION_R = "El cliente con id " . $id . " no existe";
									$sap->RESIDENCIAFISCAL = "El cliente con id " . $id . " no existe";
									$sap->NUMREGIDTRIB = "El cliente con id " . $id . " no existe";
								}
								$sap->REGIMEN = $covestro->regimen;
								$sap->RFC_E = $covestro->rfc_e;
								$sap->NOMBRE_E = $covestro->nombre_e;
								$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
								$sap->NUMPAGO = $covestro->numpago;

								$sap->LUGAREXPEDICION = $covestro->cpostal_e;
								$sap->FOLIO = date("Y") . $row[$FOLIO];
								$sap->MONEDAPAGO = $row[$MONEDAPAGO];
								$sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
								$sap->MONTOPAGOMXN = str_replace("-", "", $row[$MONTOPAGOMXN]);
								$sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
								$sap->TIPODOC = $row[$TIPODOC];
								$sap->FECHADOC = $row[$FECHAPAGO];
								$sap->ASSIGNMENT = $row[$ASSIGNMENT];
								$sap->REFERENCE = $row[$REFERENCE];
								$sap->TAX = $row[$TAX] . "/" . $row['sal_ins'] . "/" . $row['cfdi_rel'];
								$sap->FOLIOS = substr($row[$FOLIOS], -7);
								$sap->ID_DOC = substr($row[$FOLIOS], -7);
								$sap->PARCIAL = $row[$PARCIAL];
								$sap->CADENAP = Session::get('num_archivo');
								$sap->SELLOP = Session::get('nombre_archivo_sap');
								$sap->usuario = Session::get('user');
								$sap->save();
							} else {
								$sap = new SAP_Pruebas_Model;
								$sap->RFC_R = "Este pago/factura no tiene id del cliente";
								$sap->NOMBRE_R = "Este pago/factura no tiene id del cliente";
								$sap->DIRECCION_R = "Este pago/factura no tiene id del cliente";
								$sap->RESIDENCIAFISCAL = "Este pago/factura no tiene id del cliente";
								$sap->NUMREGIDTRIB = "Este pago/factura no tiene id del cliente";
								$sap->REGIMEN = $covestro->regimen;
								$sap->RFC_E = $covestro->rfc_e;
								$sap->NOMBRE_E = $covestro->nombre_e;
								$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
								$sap->NUMPAGO = $covestro->numpago;
								$sap->LUGAREXPEDICION = $covestro->cpostal_e;
								$sap->FOLIO = (int)$row[$FOLIO];
								$sap->MONEDAPAGO = $row[$MONEDAPAGO];
								$sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
								$sap->MONTOPAGOMXN = str_replace("-", "", $row[$MONTOPAGOMXN]);
								$sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
								$sap->TIPODOC = $row[$TIPODOC];
								$sap->FECHADOC = $row[$FECHAPAGO];
								$sap->TAX = $row[$TAX] . "/" . $row['sal_ins']. "/" . $row['cfdi_rel'];
								$sap->FOLIOS = substr($row[$FOLIOS], -7);
								$sap->ID_DOC = substr($row[$FOLIOS], -7);
								$sap->PARCIAL = $row[$PARCIAL];
								$sap->CADENAP = Session::get('num_archivo');
								$sap->SELLOP = Session::get('nombre_archivo_sap');
								$sap->usuario = Session::get('user');
								$sap->save();
							}
						}
					});
				}catch (\Exception $e) {
					return response()->json([
						"respuesta" => 2,
						"mensaje" => $e->getMessage(),
						"archivo" => Session::get('nombre_archivo_sap'),
						"usuario" => Session::get('user'),
						"num_archivo" => Session::get('num_archivo'),
						"seccion" => "SAP_Pruebas_Model"
					]);
				}

				try {
					Excel::selectSheets($hojaBancos)->load($archivo, function ($reader) {
						$vieneCliente = false;
						$primero = 0;
						$ultimo = 0;

						// $lay = DB::table('bancos_l_SAP')
						// 	->where('nombre', '=', 'polll')
						// 	->first();

						$FOLIO = 'clearing_folio';
						$MONTOPAGO = 'monto';
						$MONTOPAGOMXN = 'montomxn';
						$MONEDAPAGO = 'moneda';
						$TIPOCAMBIOP = 'cambio';
						$IMPINS = 0;

						$covestro = DB::table("covestro")
							->first();
						foreach ($reader->get() as $key => $row) {
							$sap = new Bancos_SAP_Model;
							$resultado1 = strlen($row[$FOLIO]);
							switch ($resultado1) {
								case 8:
									$valor1 = substr($row[$FOLIO], -3);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 9:
									$valor1 = substr($row[$FOLIO], -4);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 10:
									$valor1 = substr($row[$FOLIO], -5);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 11:
									$valor1 = substr($row[$FOLIO], -6);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 12:
									$valor1 = substr($row[$FOLIO], -7);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 13:
									$valor1 = substr($row[$FOLIO], -8);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 14:
									$valor1 = substr($row[$FOLIO], -9);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 15:
									$valor1 = substr($row[$FOLIO], -10);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 16:
									$valor1 = substr($row[$FOLIO], -11);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								case 17:
									$valor1 = substr($row[$FOLIO], -12);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
								default:
									$valor1 = substr($row[$FOLIO], 5);
									$sap->clearing_document = (int)date("Y") . $valor1;
									break;
							}
							$sap->monto = str_replace("-", "", $row[$MONTOPAGO]) . "/" . $row['sal_ins'];
							$sap->montomxn = str_replace("-", "", $row[$MONTOPAGOMXN]) . "/" . $row['sal_ins'];
							$sap->monedaP = $row[$MONEDAPAGO];
							$sap->tipocambioP = str_replace("-", "", $row[$TIPOCAMBIOP]);
							$sap->usuario = Session::get('user');
							$sap->save();
						}
					});
				}catch (\Exception $e) {
					return response()->json([
						"respuesta" => 2,
						"mensaje" => $e->getMessage(),
						"archivo" => Session::get('nombre_archivo_sap'),
						"usuario" => Session::get('user'),
						"num_archivo" => Session::get('num_archivo'),
						"seccion" => "BANCOS_SAP_MODEL"
					]);
				}
			}

			$res = DB::table('temporal_sap')
				->select('FOLIO')
				->first();

			return response()->json([
				"respuesta" => 1,
				"mensaje" => $res,
				"archivo" => Session::get('nombre_archivo_sap')
			]);
		} catch (\Exception $e) {
			return response()->json([
				"respuesta" => 2,
				"mensaje" => $e->getMessage(),
				"archivo" => Session::get('nombre_archivo_sap'),
				"usuario" => Session::get('user'),
				// "num_archivo" => Session::get('num_archivo')
			]);
		}
	}

	public function guardarPruebaForm(Request $request)
	{
		try {
			$covestro = DB::table("covestro")
				->first();
			$sap = new Bancos_SAP_Model;
			$resultado1 = strlen($request->get('folio'));
			switch ($resultado1) {
				case 8:
					$valor1 = substr($request->get('folio'), -3);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 9:
					$valor1 = substr($request->get('folio'), -4);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 10:
					$valor1 = substr($request->get('folio'), -5);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 11:
					$valor1 = substr($request->get('folio'), -6);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 12:
					$valor1 = substr($request->get('folio'), -7);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 13:
					$valor1 = substr($request->get('folio'), -8);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 14:
					$valor1 = substr($request->get('folio'), -9);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 15:
					$valor1 = substr($request->get('folio'), -10);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 16:
					$valor1 = substr($request->get('folio'), -11);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				case 17:
					$valor1 = substr($request->get('folio'), -12);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
				default:
					$valor1 = substr($request->get('folio'), 5);
					$sap->clearing_document = (int)date("Y") . $valor1;
					break;
			}
			$sap->monto = str_replace("-", "", $request->get('sal_ant')) . "/" . $request->get('sal_ins');
			$sap->montomxn = str_replace("-", "", $request->get('sal_pagado')) . "/" . $request->get('sal_ins');
			$sap->monedaP = $request->get('sal_ins');
			$sap->tipocambioP = str_replace("-", "", $request->get('tipo_cambio'));
			$sap->usuario = Session::get('user');
			$sap->save();




			return response()->json([
				"respuesta" => 1,
				"mensaje" => "Todo bien al 100",
				"archivo" => Session::get('num_archivo')
			]);
		} catch (\Exception $e) {
			return response()->json([
				"respuesta" => 2,
				"mensaje" => $e->getMessage(),
				"archivo" => Session::get('num_archivo')
			]);
		}
	}
	public function guardarPruebaEsp(Request $request)
	{
		if (
			$request->get('folio_factura') == '' || $request->get('doc_date') == '' || $request->get('fechap') == ''
			|| $request->get('parcialidad') == '' || $request->get('tipo_cambio') == '' || $request->get('sal_ant') == ''
			|| $request->get('sal_pagado') == '' || $request->get('sal_ins') == '' || $request->get('reference') == ''
			|| $request->get('assignment') == '' || $request->get('tax') == ''
		) {
			return response()->json([
				"respuesta" => 2,
				"mensaje" => "Uno de los datos obligatorios esta vacio"
			]);
		} else {
			DB::table("excel_sap")
				->where("integrado", "=", 3)
				->where("correo", "=", Session::get("user"))
				->delete();

			try {
				$num_archivo = 0;

				$num_archivo = $num_archivo + 1;

				$covestro = DB::table("covestro")
					->first();
				if ($request->get('idcliente') != "") {
					$sap = new SAP_Pruebas_Model;
					$sap->id_cliente = $request->get('idcliente');
					$id = $request->get('idcliente');
					$existe = DB::table("clientes")
						->where("id_cliente", '=', $id)
						->count();

					if ($existe >= 1) {
						$cliente = DB::table("clientes")
							->where("id_cliente", '=', $id)
							->first();

						if ($cliente->residenciafiscal != "MX") {
							$sap->RFC_R = "XEXX010101000";
							$sap->NOMBRE_R = $cliente->nombre_c;
							$sap->DIRECCION_R = $cliente->direccion_c;
							$residencias = DB::table("residencia")
								->get();

							foreach ($residencias as $resi) {
								if ($resi->equivalencia == $cliente->residenciafiscal) {
									$sap->RESIDENCIAFISCAL = $resi->resid;
								}
							}

							$sap->NUMREGIDTRIB = $request->get('numreg');
						} else {
							$sap->RFC_R = $cliente->rfc_c;
							$sap->NOMBRE_R = $cliente->nombre_c;
							$sap->DIRECCION_R = $cliente->direccion_c;
							$sap->RESIDENCIAFISCAL = "";
							$sap->NUMREGIDTRIB = "";
						}
					} else {
						$sap->RFC_R = "El cliente con id " . $id . " no existe";
						$sap->NOMBRE_R = "El cliente con id " . $id . " no existe";
						$sap->DIRECCION_R = "El cliente con id " . $id . " no existe";
						$sap->RESIDENCIAFISCAL = "El cliente con id " . $id . " no existe";
						$sap->NUMREGIDTRIB = "El cliente con id " . $id . " no existe";
					}
					$sap->REGIMEN = $covestro->regimen;
					$sap->RFC_E = $covestro->rfc_e;
					$sap->NOMBRE_E = $covestro->nombre_e;
					$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
					$sap->NUMPAGO = $covestro->numpago;

					$sap->LUGAREXPEDICION = $covestro->cpostal_e;
					$sap->FOLIO = date("Y") . $request->get('folio');
					$sap->MONEDAPAGO = $request->get('moneda');
					$sap->MONTOPAGO = str_replace("-", "", $request->get('sal_ant'));
					$sap->MONTOPAGOMXN = str_replace("-", "", $request->get('sal_pagado'));
					$sap->TIPOCAMBIOP = str_replace("-", "", $request->get('tipo_cambio'));
					$sap->TIPODOC = $request->get('dtipo');
					$sap->FECHADOC = $request->get('fechap');
					$sap->ASSIGNMENT = $request->get('assignment');
					$sap->REFERENCE = $request->get('reference');
					$sap->TAX = $request->get('tax') . "/" . $request->get('sal_ins') . "/" . $request['cfdi_rel'];
					$sap->FOLIOS = substr($request->get('folio_factura'), -7);
					$sap->ID_DOC = substr($request->get('folio_factura'), -7);
					$sap->PARCIAL = $request->get('parcialidad');
					$sap->CADENAP = Session::get('num_archivo');
					$sap->SELLOP = 'complementoEsp';
					$sap->usuario = Session::get('user');
					$sap->save();
				} else {
					$sap = new SAP_Pruebas_Model;
					$sap->RFC_R = "Este pago/factura no tiene id del cliente";
					$sap->NOMBRE_R = "Este pago/factura no tiene id del cliente";
					$sap->DIRECCION_R = "Este pago/factura no tiene id del cliente";
					$sap->RESIDENCIAFISCAL = "Este pago/factura no tiene id del cliente";
					$sap->NUMREGIDTRIB = "Este pago/factura no tiene id del cliente";
					$sap->REGIMEN = $covestro->regimen;
					$sap->RFC_E = $covestro->rfc_e;
					$sap->NOMBRE_E = $covestro->nombre_e;
					$sap->DIRECCION_E = $covestro->calle_e . " " . $covestro->numext_e . " " . $covestro->numint_e . ", COLONIA " . $covestro->colonia_e . ", CP. " . $covestro->cpostal_e;
					$sap->NUMPAGO = $covestro->numpago;
					$sap->LUGAREXPEDICION = $covestro->cpostal_e;
					$sap->FOLIO = (int)$request->get('folio');
					$sap->MONEDAPAGO = $request->get('moneda');
					$sap->MONTOPAGO = str_replace("-", "", $request->get('sal_ant'));
					$sap->MONTOPAGOMXN = str_replace("-", "", $request->get('sal_pagado'));
					$sap->TIPOCAMBIOP = str_replace("-", "", $request->get('tipo_cambio'));
					$sap->TIPODOC = $request->get('dtipo');
					$sap->FECHADOC = $request->get('fechap');
					$sap->TAX = $request->get('tax') . "/" . $request->get('sal_ins') . "/" . $request['cfdi_rel'];
					$sap->FOLIOS = substr($request->get('folio_factura'), -7);
					$sap->ID_DOC = substr($request->get('folio_factura'), -7);
					$sap->PARCIAL = $request->get('parcialidad');
					$sap->CADENAP = Session::get('num_archivo');
					$sap->SELLOP = 'complementoEsp';
					$sap->usuario = Session::get('user');
					$sap->save();
				}

				$res = DB::table('temporal_sap')
					->select('FOLIO')
					->first();

				return response()->json([
					"respuesta" => 1,
					"mensaje" => $res,
					"archivo" => Session::get('num_archivo')
				]);
			} catch (\Exception $e) {
				return response()->json([
					"respuesta" => 2,
					"mensaje" => $e->getMessage(),
					"archivo" => Session::get('num_archivo')
				]);
			}
		}
	}

	public function guardarDatosEsp(Request $request)
	{
		//-------------variables a utilizar para comprobar las acciones sql server
		$elusuario = Session::get('user');
		$unoactual = "";
		$dosactual = "";
		$tresactual = "";
		$cuatroactual = "";
		$cincoactual = "";
		$seisactual = "";
		$sieteactual = "";
		$ochoactual = "";
		$mostrar = "";
		$ques = "";
		//-----------------
		try {
			$clearing = "";
			$fact = "";
			$acum = 0;
			$acum2 = 0;
			$hoja2cambio = "";
			$hoja2moneda = "";
			$dz = false;
			$prueba = DB::table('temporal_SAP')
				->where('usuario', '=', Session::get('user'))
				->get();

			foreach ($prueba as $p) {
				$hoja2cambio = "";
				$hoja2moneda = "";
				$monedita = "";
				$dividir = explode(".", $p->FECHADOC);
				if ($p->TIPODOC == "DZ") {
					if ($p->FOLIOS == 0 || $p->FOLIOS == "" || is_null($p->FOLIOS) || $p->FOLIOS == "0" || $p->FOLIOS == "#") {
						$dz = true;
						$monedita = $p->MONEDAPAGO;
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
						$pago->fechabus = $dividir[2] . "-" . $dividir[1] . "-" . $dividir[0] . " 12:00:00";
						$pago->assignment = $p->ASSIGNMENT;
						$pago->reference = $p->REFERENCE;
						$pago->tipocambioP = $p->TIPOCAMBIOP;
						if ($p->MONEDAPAGO != "MXN") {
							$monto = str_replace(" ", "", $p->MONTOPAGO);
						} else {
							$monto = str_replace(" ", "", $p->MONTOPAGOMXN);
						}
						$monto = str_replace("$", "", $monto);
						$monto = str_replace(",", "", $monto);
						$monto = str_replace("MXN", "", $monto);
						$monto = str_replace("mxn", "", $monto);
						if ($monto < 0) {
							$pago->signo = "-";
						} else {
							$pago->signo = "+";
						}
						$dividirtax = explode("/", $p->tax);
						$monto = str_replace("-", "", $monto);
						if ($p->MONEDAPAGO != "MXN") {
							$pago->montoP = (float)$monto - $acum . "/" . $dividirtax[1];
						} else {
							$pago->montoP = (float)$monto - $acum2 . "/" . $dividirtax[1];
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
						$pago->id_es = $p->CADENAP;
						$pago->save();

						$acum = 0;
						$acum2 = 0;
						$clearing = $p->FOLIO;
						$precio = "";

						/*$hoja2 = DB::table("bancos_sap")
						//->where("usuario", "=", Session::get("usuario"))
						->where("clearing_document", "=", $p->FOLIO)
						->first();

					$cosa = $hoja2->clearing_document;*/

						$hoja2 = DB::table("bancos_sap")
							//->where("usuario", "=", Session::get("usuario"))
							->where("clearing_document", "=", $p->FOLIO)
							->count();

						if ($hoja2 > 0) {
							$hoja2 = DB::table("bancos_sap")
								//->where("usuario", "=", Session::get("usuario"))
								->where("clearing_document", "=", $p->FOLIO)
								->first();

							$hoja2cambio = $hoja2->tipocambioP;
							$hoja2moneda = $hoja2->monedaP;
						}

						$cr2 = DB::table("credito")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$fl2 = DB::table("facturas_liquidadas")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$par2 = DB::table("parcialidades")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						foreach ($cr2 as $c) {
							if ($hoja2moneda != "" && $c->moneda != $monedita) {
								try {
									$unoactual = DB::table("credito")
										->where("id_cre", "=", $c->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $df) {
									$unoactual = $df->getMessage();
								}
							}
						}

						foreach ($fl2 as $f) {
							if ($hoja2moneda != "" && $f->moneda != $monedita) {
								try {
									$dosactual = DB::table("facturas_liquidadas")
										->where("id_cre", "=", $f->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $bf) {
									$dosactual = $bf->getMessage();
								}
							}
						}

						foreach ($par2 as $p) {
							if ($hoja2moneda != "" && $p->moneda != $monedita) {
								try {
									$tresactual = DB::table("parcialidades")
										->where("id_par", "=", $p->id_par)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $rt) {
									$tresactual = $rt->getMessage();
								}
							}
						}
					} else {
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
				} elseif ($p->TIPODOC == "DC") {
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
					$pago->fechabus = $dividir[2] . "-" . $dividir[1] . "-" . $dividir[0] . " 12:00:00";
					$pago->assignment = $p->ASSIGNMENT;
					$pago->reference = $p->REFERENCE;
					$pago->tipocambioP = $p->TIPOCAMBIOP;
					$monto = str_replace(" ", "", $p->MONTOPAGO);
					$monto = str_replace("$", "", $monto);
					$monto = str_replace(",", "", $monto);
					$monto = str_replace("MXN", "", $monto);
					$monto = str_replace("mxn", "", $monto);
					if ($monto < 0) {
						$pago->signo = "-";
					} else {
						$pago->signo = "+";
					}
					$monto = str_replace("-", "", $monto);
					$dividirtax = explode("/", $p->tax);
					$pago->montoP = (float)$monto . "/" . $dividirtax[1];
					$pago->numeroperP = "";
					$pago->rfcctaord = "";
					$pago->bancoordext = "";
					$pago->ctaord = "";
					$pago->cataben = "";
					$pago->rfc_c = $p->RFC_R;
					$pago->nombre_c = $p->NOMBRE_R;
					$pago->id_cliente = $p->id_cliente;
					$pago->rfc_e = $p->RFC_E;
					$pago->nombre_e = $p->NOMBRE_E;
					$pago->timbrado = "0";
					$pago->id_es = $p->CADENAP;
					$pago->save();
				} elseif ($p->TIPODOC ==  "AB") {
					$hay = DB::table("parcialidades")
						->where("folio", "=", $p->FOLIOS)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($hay < 1) {
						$parti = explode(":", $p->PARCIAL);
						if (count($parti) == 3) {
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
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							$dividirtax = explode("/", $p->tax);
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();
							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							$fechita = explode(".", $p->FECHADOC);
						} else {
							$numpar = str_split($p->PARCIAL);
							if ($p->MONEDAPAGO != "MXN") {
								$precio = str_replace("$", "", $p->MONTOPAGO);
							} else {
								$precio = str_replace("$", "", $p->MONTOPAGOMXN);
							}
							$parcial = new Parcialidades;
							$parcial->tipcambio = $p->TIPOCAMBIOP;
							$parcial->moneda = $p->MONEDAPAGO;
							if (is_numeric($numpar[0])) {
								$parcial->numparcialidad = $numpar[0];
							} else {
								$parcial->numparcialidad = "1";
							}
							$parcial->impsaldoant = $precio;
							$parcial->imppagado = $precio;
							$dividirtax = explode("/", $p->tax);
							$parcial->impsaldoins = $dividirtax[1];
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();

							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							$fechita = explode(".", $p->FECHADOC);
						}
					}
				} elseif ($p->TIPODOC == "RV") {
					$existe = DB::table('factura')
						->where('folio', '=', $p->FOLIOS)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					$fechita = explode(".", $p->FECHADOC);
					if ($existe < 1) {
						$factura = new Facturas;
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$factura = new Facturas;
						$precio = str_replace("$", "", $p->MONTOPAGO);
						$factura->folio = $p->FOLIOS;
						$factura->monto = $precio;
						$factura->moneda = $p->MONEDAPAGO;
						$dividirtax = explode("/", $p->tax);
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();
								$factura->tipo_cambio =  $tipo_de_cambio->tipocambioP;
								$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
								if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) - (((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $tipo_de_cambio->tipocambioP;
								}
							} else {
								$factura->tipo_cambio =  $p->TIPOCAMBIOP;
								$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
								if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) - (((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $p->TIPOCAMBIOP;
								}
							}
						} else {
							$factura->tipo_cambio =  "1.00";
							$factura->fecha = $fechita[2] . "-" . $fechita[1] . "-" . $fechita[0];
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "16";
								$factura->impuesto = (float)$precio - (((float)$precio) / 1.16);
								$factura->sin_impuesto = ((float)$precio) / 1.16;
							} else {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "0";
								$factura->impuesto = "0";
								$factura->sin_impuesto = $precio;
							}
						}
						$factura->id_cliente = $p->id_cliente;
						$factura->nombre_c = $p->NOMBRE_R;
						$factura->residencia = $p->RESIDENCIAFISCAL;
						$factura->clearings = $p->FOLIO;
						$factura->save();
					} else {
						$varios = DB::table("factura")
							->where("folio", "=", $p->FOLIOS)
							->first();

						try {
							$cuatroactual = DB::table("factura")
								->where("folio", "=", $p->FOLIOS)
								->update([
									"clearings" => $varios->clearings . ", " . $p->FOLIO
								]);
						} catch (\Exception $nh) {
							$cuatroactual = $nh->getMessage();
						}
					}
					$existe = DB::table('parcialidades')
						->where('folio', '=', $p->FOLIOS)
						->where('clearing_document', '=', $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($existe < 1) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcial->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();

						$parcia = new Liquidadas_Model;
						$parcia->tipcambio = $p->TIPOCAMBIOP;
						$parcia->moneda = $p->MONEDAPAGO;
						$parcia->numparcialidad = "1";
						$parcia->impsaldoant = $precio;
						$parcia->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcia->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						$parcia->folio = $p->FOLIOS;
						$parcia->clearing_document = $p->FOLIO;
						$parcia->id_es = $p->CADENAP;
						$parcia->save();
					} elseif ($existe % 2 == 0) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcial->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();
					}
				} elseif ($p->TIPODOC == "RW") {
					if ($p->MONEDAPAGO != "MXN") {
						$precio = str_replace("$", "", $p->MONTOPAGO);
					} else {
						$precio = str_replace("$", "", $p->MONTOPAGOMXN);
					}
					$assig = substr($p->ASSIGNMENT, 13);
					$encuentra = DB::table('parcialidades')
						->where("imppagado", "=", $precio)
						->where("folio", "=", $assig)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();

					if ($encuentra == 1) {
						$pre = DB::table('parcialidades')
							->where("imppagado", "=", $precio)
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->first();

						$encuentra = DB::table('parcialidades')
							->where("id_par", "=", $pre->id_par)
							->delete();
					} else {
						$encuentra = DB::table('parcialidades')
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->count();

						if ($encuentra == 1) {
							$pre = DB::table('parcialidades')
								->where("folio", "=", $assig)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->first();
							try {
								$cincoactual = DB::table('parcialidades')
									->where('id_par', '=', $pre->id_par)
									->update([
										"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
										"imppagado" => (float)$pre->imppagado - (float)$precio,
									]);
							} catch (\Exception $ft) {
								$cincoactual = $ft->getMessage();
							}
						} else {
							$encuentra = DB::table('parcialidades')
								->where("imppagado", "=", $precio)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->count();

							if ($encuentra >= 1) {
								$pre = DB::table('parcialidades')
									->where("imppagado", "=", $precio)
									->where("clearing_document", "=", $p->FOLIO)
									->where('id_es', '=', $p->CADENAP)
									->orderBy('id_par', 'desc')
									->first();

								$encuentra = DB::table('parcialidades')
									->where("id_par", "=", $pre->id_par)
									->delete();
							} else {
								$encuentra = DB::table('parcialidades')
									->where("clearing_document", '=', $p->FOLIO)
									->max("imppagado");

								if ($encuentra >= 1) {
									$pre = DB::table('parcialidades')
										->where("clearing_document", "=", $p->FOLIO)
										->where("imppagado", "=", $encuentra)
										->where('id_es', '=', $p->CADENAP)
										->first();
									try {
										$seisactual = DB::table('parcialidades')
											->where('id_par', '=', $pre->id_par)
											->update([
												"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
												"imppagado" => (float)$pre->imppagado - (float)$precio,
											]);
									} catch (\Exception $nop) {
										$seisactual = $nop->getMessage();
									}
								}
							}
						}
					}
				}
			}
			// return response()->json([
			// 	"respuesta" => 2,
			// 	"mensaje" => "Creo que si hace el foreach",
			// 	"archivo" => Session::get('num_archivo')
			// ]);
			try {
				$sieteactual = DB::table("bancos_sap")
					->where("id_es", "=", null)
					->update([
						"id_es" => Session::get("num_archivo")
					]);
			} catch (\Exception $gh) {
				$sieteactual = $gh->getMessage();
			}

			try {
				$ochoactual = DB::table("excel_SAP")
					->where("integrado", "=", 3)
					->update([
						"integrado" => 0
					]);
			} catch (\Exception $jk) {
				$ochoactual = $jk->getMessage();
			}
			try {
				$mostrar = DB::table('temporal_SAP')
					->where('usuario', '=', Session::get('user'))
					->count();
			} catch (\Exception $clo) {
				$mostrar = $clo->getMessage();
			}

			if ($mostrar > 0) {
				try {
					$mostrar2 = DB::table('temporal_SAP')
						->where('usuario', '=', Session::get('user'))
						->delete();
				} catch (\Exception $th) {
					$mostrar2 = $th->getMessage();
				}

				return response()->json([
					"respuesta" => "0",
					"sihay" => $mostrar,
					"elimina1" => $mostrar2, //si elimino los datos de la tabla temporal_Sap
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "todo chido",
					"mensaje" => "Todo chido al 100"
				]);
			} else {
				try {
					$ques = DB::table("excel_SAP")
						->where("integrado", "=", 3)
						->delete();
				} catch (\Exception $xr) {
					$ques = $xr->getMessage();
				}

				return response()->json([
					"respuesta" => "2",
					"sihay" => $mostrar,
					"quees" => $ques,
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "no hay datos cargados",
					"mensaje" => "no detecto nada de la tabla temporal"
				]);
			}
		} catch (\Exception $haber) {
			$mensaje = $haber->getMessage();
			return response()->json([
				"respuesta" => "2",
				"sihay" => $mostrar,
				"quees" => $ques,
				"unoup" => $unoactual, //actaliza tabla creditlocaldr
				"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
				"tresup" => $tresactual, //actaliza tabla parcialidades
				"cuatroup" => $cuatroactual, //actaliza tabla factura
				"cincoup" => $cincoactual, //actaliza tabla parcialidades
				"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
				"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
				"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
				"user" => $elusuario, // obtiene el correo de quien se logueo
				"mensajito" => $mensaje,
				"mensaje" => "Algo esta mal dentro del try"
			]);
		}
	}

	public function guardarDatosForm(Request $request)
	{
		//-------------variables a utilizar para comprobar las acciones sql server
		$elusuario = Session::get('user');
		$unoactual = "";
		$dosactual = "";
		$tresactual = "";
		$cuatroactual = "";
		$cincoactual = "";
		$seisactual = "";
		$sieteactual = "";
		$ochoactual = "";
		$mostrar = "";
		$ques = "";
		//-----------------
		try {
			$clearing = "";
			$fact = "";
			$acum = 0;
			$acum2 = 0;
			$hoja2cambio = "";
			$hoja2moneda = "";
			$dz = false;
			$prueba = DB::table('temporal_SAP')
				->where('usuario', '=', Session::get('user'))
				->get();

			foreach ($prueba as $p) {
				$hoja2cambio = "";
				$hoja2moneda = "";
				$monedita = "";
				//$dividir = explode(".", $p->FECHADOC);
				if ($p->TIPODOC == "DZ") {
					if ($p->FOLIOS == 0 || $p->FOLIOS == "" || is_null($p->FOLIOS) || $p->FOLIOS == "0" || $p->FOLIOS == "#") {
						$dz = true;
						$monedita = $p->MONEDAPAGO;
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
						$pago->fechabus = $p->FECHADOC. " 12:00:00";
						$pago->assignment = $p->ASSIGNMENT;
						$pago->reference = $p->REFERENCE;
						$pago->tipocambioP = $p->TIPOCAMBIOP;
						if ($p->MONEDAPAGO != "MXN") {
							$monto = str_replace(" ", "", $p->MONTOPAGO);
						} else {
							$monto = str_replace(" ", "", $p->MONTOPAGOMXN);
						}
						$monto = str_replace("$", "", $monto);
						$monto = str_replace(",", "", $monto);
						$monto = str_replace("MXN", "", $monto);
						$monto = str_replace("mxn", "", $monto);
						if ($monto < 0) {
							$pago->signo = "-";
						} else {
							$pago->signo = "+";
						}
						$dividirtax = explode("/", $p->tax);
						$monto = str_replace("-", "", $monto);
						if ($p->MONEDAPAGO != "MXN") {
							$pago->montoP = (float)$monto - $acum . "/" . $dividirtax[1];
						} else {
							$pago->montoP = (float)$monto - $acum2 . "/" . $dividirtax[1];
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
						$pago->id_es = $p->CADENAP;
						$pago->save();

						$acum = 0;
						$acum2 = 0;
						$clearing = $p->FOLIO;
						$precio = "";

						/*$hoja2 = DB::table("bancos_sap")
						//->where("usuario", "=", Session::get("usuario"))
						->where("clearing_document", "=", $p->FOLIO)
						->first();

					$cosa = $hoja2->clearing_document;*/

						$hoja2 = DB::table("bancos_sap")
							//->where("usuario", "=", Session::get("usuario"))
							->where("clearing_document", "=", $p->FOLIO)
							->count();

						if ($hoja2 > 0) {
							$hoja2 = DB::table("bancos_sap")
								//->where("usuario", "=", Session::get("usuario"))
								->where("clearing_document", "=", $p->FOLIO)
								->first();

							$hoja2cambio = $hoja2->tipocambioP;
							$hoja2moneda = $hoja2->monedaP;
						}

						$cr2 = DB::table("credito")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$fl2 = DB::table("facturas_liquidadas")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						$par2 = DB::table("parcialidades")
							->where("clearing_document", "=", $p->FOLIO)
							->get();

						foreach ($cr2 as $c) {
							if ($hoja2moneda != "" && $c->moneda != $monedita) {
								try {
									$unoactual = DB::table("credito")
										->where("id_cre", "=", $c->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $df) {
									$unoactual = $df->getMessage();
								}
							}
						}

						foreach ($fl2 as $f) {
							if ($hoja2moneda != "" && $f->moneda != $monedita) {
								try {
									$dosactual = DB::table("facturas_liquidadas")
										->where("id_cre", "=", $f->id_cre)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $bf) {
									$dosactual = $bf->getMessage();
								}
							}
						}

						foreach ($par2 as $p) {
							if ($hoja2moneda != "" && $p->moneda != $monedita) {
								try {
									$tresactual = DB::table("parcialidades")
										->where("id_par", "=", $p->id_par)
										->update([
											"tipcambio" => 1 / $hoja2cambio //cambio de variable 18/08/20
										]);
								} catch (\Exception $rt) {
									$tresactual = $rt->getMessage();
								}
							}
						}
					} else {
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
				} elseif ($p->TIPODOC == "DC") {
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
					$pago->fechabus = $p->FECHADOC . " 12:00:00";
					$pago->assignment = $p->ASSIGNMENT;
					$pago->reference = $p->REFERENCE;
					$pago->tipocambioP = $p->TIPOCAMBIOP;
					$monto = str_replace(" ", "", $p->MONTOPAGO);
					$monto = str_replace("$", "", $monto);
					$monto = str_replace(",", "", $monto);
					$monto = str_replace("MXN", "", $monto);
					$monto = str_replace("mxn", "", $monto);
					if ($monto < 0) {
						$pago->signo = "-";
					} else {
						$pago->signo = "+";
					}
					$monto = str_replace("-", "", $monto);
					$dividirtax = explode("/", $p->tax);
					$pago->montoP = (float)$monto . "/" . $dividirtax[1];
					$pago->numeroperP = "";
					$pago->rfcctaord = "";
					$pago->bancoordext = "";
					$pago->ctaord = "";
					$pago->cataben = "";
					$pago->rfc_c = $p->RFC_R;
					$pago->nombre_c = $p->NOMBRE_R;
					$pago->id_cliente = $p->id_cliente;
					$pago->rfc_e = $p->RFC_E;
					$pago->nombre_e = $p->NOMBRE_E;
					$pago->timbrado = "0";
					$pago->id_es = $p->CADENAP;
					$pago->save();
				} elseif ($p->TIPODOC ==  "AB") {
					$hay = DB::table("parcialidades")
						->where("folio", "=", $p->FOLIOS)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($hay < 1) {
						$parti = explode(":", $p->PARCIAL);
						if (count($parti) == 3) {
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
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							$dividirtax = explode("/", $p->tax);
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();
							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							//$fechita = explode(".", $p->FECHADOC);
						} else {
							$numpar = str_split($p->PARCIAL);
							if ($p->MONEDAPAGO != "MXN") {
								$precio = str_replace("$", "", $p->MONTOPAGO);
							} else {
								$precio = str_replace("$", "", $p->MONTOPAGOMXN);
							}
							$parcial = new Parcialidades;
							$parcial->tipcambio = $p->TIPOCAMBIOP;
							$parcial->moneda = $p->MONEDAPAGO;
							if (is_numeric($numpar[0])) {
								$parcial->numparcialidad = $numpar[0];
							} else {
								$parcial->numparcialidad = "1";
							}
							$parcial->impsaldoant = $precio;
							$parcial->imppagado = $precio;
							$dividirtax = explode("/", $p->tax);
							$parcial->impsaldoins = $dividirtax[1];
							if ($p->MONTOPAGO < 0) {
								$parcial->signo = "-";
							} else {
								$parcial->signo = "+";
							}
							$parcial->folio = $p->FOLIOS;
							$parcial->clearing_document = $p->FOLIO;
							$parcial->rfc_c = $p->RFC_R;
							$parcial->nombre_c = $p->NOMBRE_R;
							$parcial->id_es = $p->CADENAP;
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$parcial->tipo_impuesto = 16;
							} else {
								$parcial->tipo_impuesto = "0";
							}
							if ($p->MONEDAPAGO != "MXN") {
								if ($tipo_de_cambio > 0) {
									$tipo_de_cambio = DB::table('bancos_SAP')
										->where('clearing_document', '=', $p->FOLIO)
										->orderBy('id_bsap', 'desc')
										->first();

									$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
								} else {
									$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
								}
							} else {
								$parcial->tipo_cambio_bancos = "1.00";
							}
							$parcial->save();

							$existe = DB::table('factura')
								->where('folio', '=', $p->FOLIOS)
								->count();
							//$fechita = explode(".", $p->FECHADOC);
						}
					}
				} elseif ($p->TIPODOC == "RV") {
					$existe = DB::table('factura')
						->where('folio', '=', $p->FOLIOS)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					//$fechita = explode(".", $p->FECHADOC);
					if ($existe < 1) {
						$factura = new Facturas;
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$factura = new Facturas;
						$precio = str_replace("$", "", $p->MONTOPAGO);
						$factura->folio = $p->FOLIOS;
						$factura->monto = $precio;
						$factura->moneda = $p->MONEDAPAGO;
						$dividirtax = explode("/", $p->tax);
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();
								$factura->tipo_cambio =  $tipo_de_cambio->tipocambioP;
								$factura->fecha = $p->FECHADOC;
								if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) - (((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$tipo_de_cambio->tipocambioP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $tipo_de_cambio->tipocambioP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $tipo_de_cambio->tipocambioP;
								}
							} else {
								$factura->tipo_cambio =  $p->TIPOCAMBIOP;
								$factura->fecha = $p->FECHADOC;
								if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
									$factura->monto_mxn = (float)$precio * (float)$p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "16";
									$factura->impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) - (((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16);
									$factura->sin_impuesto = ((float)$precio * (float)$p->TIPOCAMBIOP) / 1.16;
								} else {
									$factura->monto_mxn = (float)$precio * $p->TIPOCAMBIOP;
									$factura->tipo_impuesto = "0";
									$factura->impuesto = "0";
									$factura->sin_impuesto = (float)$precio * $p->TIPOCAMBIOP;
								}
							}
						} else {
							$factura->tipo_cambio =  "1.00";
							$factura->fecha = $p->FECHADOC;
							if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "16";
								$factura->impuesto = (float)$precio - (((float)$precio) / 1.16);
								$factura->sin_impuesto = ((float)$precio) / 1.16;
							} else {
								$factura->monto_mxn = (float)$precio;
								$factura->tipo_impuesto = "0";
								$factura->impuesto = "0";
								$factura->sin_impuesto = $precio;
							}
						}
						$factura->id_cliente = $p->id_cliente;
						$factura->nombre_c = $p->NOMBRE_R;
						$factura->residencia = $p->RESIDENCIAFISCAL;
						$factura->clearings = $p->FOLIO;
						$factura->save();
					} else {
						$varios = DB::table("factura")
							->where("folio", "=", $p->FOLIOS)
							->first();

						try {
							$cuatroactual = DB::table("factura")
								->where("folio", "=", $p->FOLIOS)
								->update([
									"clearings" => $varios->clearings . ", " . $p->FOLIO
								]);
						} catch (\Exception $nh) {
							$cuatroactual = $nh->getMessage();
						}
					}
					$existe = DB::table('parcialidades')
						->where('folio', '=', $p->FOLIOS)
						->where('clearing_document', '=', $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();
					$tipo_de_cambio = DB::table('bancos_SAP')
						->where('clearing_document', '=', $p->FOLIO)
						->count();
					if ($existe < 1) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcial->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();

						$parcia = new Liquidadas_Model;
						$parcia->tipcambio = $p->TIPOCAMBIOP;
						$parcia->moneda = $p->MONEDAPAGO;
						$parcia->numparcialidad = "1";
						$parcia->impsaldoant = $precio;
						$parcia->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcia->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						$parcia->folio = $p->FOLIOS;
						$parcia->clearing_document = $p->FOLIO;
						$parcia->id_es = $p->CADENAP;
						$parcia->save();
					} elseif ($existe % 2 == 0) {
						if ($p->MONEDAPAGO != "MXN") {
							$precio = str_replace("$", "", $p->MONTOPAGO);
						} else {
							$precio = str_replace("$", "", $p->MONTOPAGOMXN);
						}
						$parcial = new Parcialidades;
						$parcial->tipcambio = $p->TIPOCAMBIOP;
						$parcial->moneda = $p->MONEDAPAGO;
						$parcial->numparcialidad = "1";
						$parcial->impsaldoant = $precio;
						$parcial->imppagado = $precio;
						$dividirtax = explode("/", $p->tax);
						$parcial->impsaldoins = $dividirtax[1].'/'.$dividirtax[2];
						if ($p->MONTOPAGO < 0) {
							$parcial->signo = "-";
						} else {
							$parcial->signo = "+";
						}
						$parcial->folio = $p->FOLIOS;
						$parcial->clearing_document = $p->FOLIO;
						$parcial->rfc_c = $p->RFC_R;
						$parcial->nombre_c = $p->NOMBRE_R;
						$parcial->id_es = $p->CADENAP;
						if ($dividirtax[0] == "A3" || $dividirtax[0] == "A9") {
							$parcial->tipo_impuesto = 16;
						} else {
							$parcial->tipo_impuesto = "0";
						}
						if ($p->MONEDAPAGO != "MXN") {
							if ($tipo_de_cambio > 0) {
								$tipo_de_cambio = DB::table('bancos_SAP')
									->where('clearing_document', '=', $p->FOLIO)
									->orderBy('id_bsap', 'desc')
									->first();

								$parcial->tipo_cambio_bancos = $tipo_de_cambio->tipocambioP;
							} else {
								$parcial->tipo_cambio_bancos = $p->TIPOCAMBIOP;
							}
						} else {
							$parcial->tipo_cambio_bancos = "1.00";
						}
						$parcial->save();
					}
				} elseif ($p->TIPODOC == "RW") {
					if ($p->MONEDAPAGO != "MXN") {
						$precio = str_replace("$", "", $p->MONTOPAGO);
					} else {
						$precio = str_replace("$", "", $p->MONTOPAGOMXN);
					}
					$assig = substr($p->ASSIGNMENT, 13);
					$encuentra = DB::table('parcialidades')
						->where("imppagado", "=", $precio)
						->where("folio", "=", $assig)
						->where("clearing_document", "=", $p->FOLIO)
						->where('id_es', '=', $p->CADENAP)
						->count();

					if ($encuentra == 1) {
						$pre = DB::table('parcialidades')
							->where("imppagado", "=", $precio)
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->first();

						$encuentra = DB::table('parcialidades')
							->where("id_par", "=", $pre->id_par)
							->delete();
					} else {
						$encuentra = DB::table('parcialidades')
							->where("folio", "=", $assig)
							->where("clearing_document", "=", $p->FOLIO)
							->where('id_es', '=', $p->CADENAP)
							->count();

						if ($encuentra == 1) {
							$pre = DB::table('parcialidades')
								->where("folio", "=", $assig)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->first();
							try {
								$cincoactual = DB::table('parcialidades')
									->where('id_par', '=', $pre->id_par)
									->update([
										"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
										"imppagado" => (float)$pre->imppagado - (float)$precio,
									]);
							} catch (\Exception $ft) {
								$cincoactual = $ft->getMessage();
							}
						} else {
							$encuentra = DB::table('parcialidades')
								->where("imppagado", "=", $precio)
								->where("clearing_document", "=", $p->FOLIO)
								->where('id_es', '=', $p->CADENAP)
								->count();

							if ($encuentra >= 1) {
								$pre = DB::table('parcialidades')
									->where("imppagado", "=", $precio)
									->where("clearing_document", "=", $p->FOLIO)
									->where('id_es', '=', $p->CADENAP)
									->orderBy('id_par', 'desc')
									->first();

								$encuentra = DB::table('parcialidades')
									->where("id_par", "=", $pre->id_par)
									->delete();
							} else {
								$encuentra = DB::table('parcialidades')
									->where("clearing_document", '=', $p->FOLIO)
									->max("imppagado");

								if ($encuentra >= 1) {
									$pre = DB::table('parcialidades')
										->where("clearing_document", "=", $p->FOLIO)
										->where("imppagado", "=", $encuentra)
										->where('id_es', '=', $p->CADENAP)
										->first();
									try {
										$seisactual = DB::table('parcialidades')
											->where('id_par', '=', $pre->id_par)
											->update([
												"impsaldoant" => (float)$pre->impsaldoant - (float)$precio,
												"imppagado" => (float)$pre->imppagado - (float)$precio,
											]);
									} catch (\Exception $nop) {
										$seisactual = $nop->getMessage();
									}
								}
							}
						}
					}
				}
			}
			try {
				$sieteactual = DB::table("bancos_sap")
					->where("id_es", "=", null)
					->update([
						"id_es" => Session::get("num_archivo")
					]);
			} catch (\Exception $gh) {
				$sieteactual = $gh->getMessage();
			}

			try {
				$ochoactual = DB::table("excel_SAP")
					->where("integrado", "=", 3)
					->update([
						"integrado" => 0
					]);
			} catch (\Exception $jk) {
				$ochoactual = $jk->getMessage();
			}
			try {
				$mostrar = DB::table('temporal_SAP')
					->where('usuario', '=', Session::get('user'))
					->count();
			} catch (\Exception $clo) {
				$mostrar = $clo->getMessage();
			}

			if ($mostrar > 0) {
				try {
					$mostrar2 = DB::table('temporal_SAP')
						->where('usuario', '=', Session::get('user'))
						->delete();
				} catch (\Exception $th) {
					$mostrar2 = $th->getMessage();
				}

				return response()->json([
					"respuesta" => "0",
					"sihay" => $mostrar,
					"elimina1" => $mostrar2, //si elimino los datos de la tabla temporal_Sap
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "todo chido"
				]);
			} else {
				try {
					$ques = DB::table("excel_SAP")
						->where("integrado", "=", 3)
						->delete();
				} catch (\Exception $xr) {
					$ques = $xr->getMessage();
				}

				return response()->json([
					"respuesta" => "2",
					"sihay" => $mostrar,
					"quees" => $ques,
					"unoup" => $unoactual, //actaliza tabla creditlocaldr
					"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
					"tresup" => $tresactual, //actaliza tabla parcialidades
					"cuatroup" => $cuatroactual, //actaliza tabla factura
					"cincoup" => $cincoactual, //actaliza tabla parcialidades
					"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
					"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
					"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
					"user" => $elusuario, // obtiene el correo de quien se logueo
					"mensajito" => "no hay datos cargados"
				]);
			}
		} catch (\Exception $haber) {
			$mensaje = $haber->getMessage();
			return response()->json([
				"respuesta" => "2",
				"sihay" => $mostrar,
				"quees" => $ques,
				"unoup" => $unoactual, //actaliza tabla creditlocaldr
				"dosup" => $dosactual, //actaliza tabla facturas_liquidadas
				"tresup" => $tresactual, //actaliza tabla parcialidades
				"cuatroup" => $cuatroactual, //actaliza tabla factura
				"cincoup" => $cincoactual, //actaliza tabla parcialidades
				"seisup" => $seisactual, //actaliza tabla parcialidades con diferente moneda
				"sieteup" => $sieteactual, //actaliza tabla Bancos_Sap
				"ochoup" => $ochoactual, //actaliza tabla Excel_Sap
				"user" => $elusuario, // obtiene el correo de quien se logueo
				"mensajito" => $mensaje
			]);
		}
	}
}
