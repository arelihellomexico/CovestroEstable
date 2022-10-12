if($p->TIPODOC == "DZ" && $p->FOLIOS == 0){
    			if($dz == false){
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
					$pago->tipocambioP = $p->TIPOCAMBIOP;			
					$pago->montoP = str_replace("-", "", $p->MONTOPAGO);
					if($p->MONTOPAGO < 0){
						$pago->signo = "-";
					}
					else{
						$pago->signo = "+";
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
					$pago->timbrado = "0";
					$pago->save();

					$clearing = $p->FOLIO;
    			}
    			else{
    				$e = DB::table('pago')
			    	->where('clearing_document', '=', $p->FOLIO)
			    	->count();
			    	if($e < 1){
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
						$pago->tipocambioP = $p->TIPOCAMBIOP;			
						$pago->montoP = str_replace("-", "", $p->MONTOPAGO);
						if($p->MONTOPAGO < 0){
							$pago->signo = "-";
						}
						else{
							$pago->signo = "+";
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
						$pago->timbrado = "0";
						$pago->save();

						$clearing = $p->FOLIO;
			    	}
    			}
    		}
    		elseif($p->TIPODOC == "DZ" && $p->FOLIOS == 0){

    		}
    		elseif($p->TIPODOC == "RV" || $p->TIPODOC == "RW"){
		    		$existe = DB::table('factura')
		    		->where('folio', '=', $p->FOLIOS)
		    		->count();
		    		if($existe < 1){
			    		$factura = new Facturas;
				    	$factura->folio = $p->FOLIOS;
				    	$factura->clearing_document = $p->FOLIO;
				    	if($factura->save()){
				    		$fact = $p->FOLIOS;
				    		$parcial = new Parcialidades;
				    		$parcial->numparcialidad = "1";
				    		$parcial->impsaldoant = str_replace("-", "", $p->MONTOPAGO);
				    		$parcial->imppagado = str_replace("-", "", $p->MONTOPAGO);
				    		if($p->MONTOPAGO < 0){
								$parcial->signo = "-";
							}
							else{
								$parcial->signo = "+";
							}
				    		$parcial->impsaldoins = 0.0;
				    		$parcial->folio = $p->FOLIOS;
				    		$parcial->save();
				    	}
					}
					else{
						$fact = $p->FOLIOS;
				    	$parcial = new Parcialidades;
				    	$parcial->numparcialidad = "1";
				    	$parcial->impsaldoant = str_replace("-", "", $p->MONTOPAGO);
				    	$parcial->imppagado = str_replace("-", "", $p->MONTOPAGO);
				    	if($p->MONTOPAGO < 0){
							$parcial->signo = "-";
						}
						else{
							$parcial->signo = "+";
						}
				    	$parcial->impsaldoins = 0.0;
				    	$parcial->folio = $p->FOLIOS;
				    	$parcial->save();
					}
    		}