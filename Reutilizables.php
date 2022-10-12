<?php
	//Posible reutilización de integración de archivos.

	/*foreach ($sap as $s){
	    	if ($s->rfc_c != "") {
	    		//Existe RFC o nombre de cliente en pago de SAP
	    		$tesoreria = DB::table("tesoreria")
				->where(DB::raw('('.$busquedaTeso.') and timbrado = 0'))
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
					$incidencia->timbrado = "No existe relación entre tesoreria y SAP";
					$incidencia->save();

					DB::table("pago")
					->where('clearing_document', '=', $s->clearing_document)
					->update(["timbrado" => "No existe relación entre tesoreria y SAP"]);
			    }
			    elseif($conteo == 1){
			    	$cfdirels = DB::table("factura")
			    	->where('clearing_document', '=', $s->clearing_document)
			    	->count();

			    	if($cfdirels > 0){
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
						$complemento->nombre_c = $s->nombre_c;
						$complemento->rfc_e = $s->rfc_e;
						$complemento->nombre_e = $s->nombre_e;
						$complemento->timbrado = "1";
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
						$incidencia->nombre_c = $s->nombre_c;
						$incidencia->rfc_e = $s->rfc_e;
						$incidencia->nombre_e = $s->nombre_e;
						$incidencia->timbrado = "Este pago no cuengta con facturas ni notas de crédito relacionadas.";
						$incidencia->save();

						DB::table("pago")
						->where('clearing_document', '=', $s->clearing_document)
						->update(["timbrado" => "Este pago no cuengta con facturas ni notas de crédito relacionadas."]);

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
						$incidencia->timbrado = "No existe relación entre tesoreria y SAP";
						$incidencia->save();

						DB::table("pago")
						->where('clearing_document', '=', $s->clearing_document)
						->update(["timbrado" => "No existe relación entre tesoreria y SAP"]);
				    }
				    elseif($conteo == 1){
				    	$cfdirels = DB::table("factura")
				    	->where('clearing_document', '=', $s->clearing_document)
				    	->count();

				    	if($cfdirels > 0){
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
							$complemento->nombre_c = $s->nombre_c;
							$complemento->rfc_e = $s->rfc_e;
							$complemento->nombre_e = $s->nombre_e;
							$complemento->timbrado = "1";
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
							$incidencia->nombre_c = $s->nombre_c;
							$incidencia->rfc_e = $s->rfc_e;
							$incidencia->nombre_e = $s->nombre_e;
							$incidencia->timbrado = "Este pago no cuengta con facturas ni notas de crédito relacionadas.";
							$incidencia->save();

							DB::table("pago")
							->where('clearing_document', '=', $s->clearing_document)
							->update(["timbrado" => "Este pago no cuengta con facturas ni notas de crédito relacionadas."]);

							DB::table("similaridades")
							->delete();
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
						$incidencia->nombre_c = $s->nombre_c;
						$incidencia->rfc_e = $s->rfc_e;
						$incidencia->nombre_e = $s->nombre_e;
						$incidencia->timbrado = "Hay ambigüedad entre tesorería y SAP. Multiples datos de tesorería se asocian con este pago de SAP.";
						$incidencia->save();

						DB::table("pago")
						->where('clearing_document', '=', $s->clearing_document)
						->update(["timbrado" => "Hay ambigüedad entre tesorería y SAP. Multiples datos de tesorería se asocian con este pago de SAP."]);

						DB::table("similaridades")
							->truncate();
				    }
			    }
	    	}
	    	else{
	    		//No existe RFC o nombre de cliente en pago de tesorería, se va a incidencia
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
				$incidencia->nombre_c = $s->nombre_c;
				$incidencia->rfc_e = $s->rfc_e;
				$incidencia->nombre_e = $s->nombre_e;
				$incidencia->timbrado = "El cliente no fue especificado en el pago o no existe.";
				$incidencia->save();

				DB::table("pago")
				->where('clearing_document', '=', $s->clearing_document)
				->update(["timbrado" => "El cliente no fue especificado en el pago o no existe."]);
	    	}
	    }*/
?>