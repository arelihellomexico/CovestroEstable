@extends('Plantilla.plantilla')
@section('title','Reporte de Pagos')
@section('barra-superior')
@section('sidemenu')

@section('contenido')

<!-- Inicio formulario -->
<div class="col-12 col-xs-12 col-sm-12 col-md-11 col-lg-12">
  <h2><b>Complemento para recepción de pagos</b></h2>
  <br />
  <h3 style="font-size: 16pt;"><b>Tesorería</b></h3>
  <hr class="underline">
  <form id="formulario" action="javascript:cargarFormulario();" method="post">
    {{csrf_field()}}

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
          <label>Selecciona el archivo de excel de tesorería a comparar</label>
          <select id="layout" name="layout" class="form-control layo">

            @foreach($layout as $l)

            <option value="{{$l->id_et}}">{{$l->nombre}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
          <label for="FormatoDePagoP">Forma de pago</label>
          <select class="form-control" id="FormatoDePagoP" name="FormatoDePagoP">
            <option value="01">01- Efectivo</option>
            <option value="02">02- Cheque nominativo</option>
            <option value="03" selected>03- Tranferencia electrónica de fondos</option>
            <option value="12">12- Dación en pago</option>
            <option value="15">15- Condonación</option>
            <option value="17">17- Compensación</option>
            <option value="25">25- Remisión de deuda</option>
            <option value="99">99- Por defir</option>
          </select>
        </div>
      </div>



      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group">
          <label for="MetodoDePagoDR">Método de pago DR</label>
          <select class="form-control" id="MetodoDePagoDR" name="MetodoDePagoDR">
            <option value="PUE">1. PUE - Pago en una sola exhibición</option>
            <option value="PIP">2. PIP - Pago inicial y parcialidades</option>
            <option value="PPD" selected>3. PPD - Pago en parcialidades o diferido</option>
          </select>
        </div>
      </div>
    </div>
</div>

<!-- Inicio formulario SAP -->
<div class="col-12 col-xs-12 col-sm-12 col-md-11 col-lg-12">
  <h3 style="font-size: 16pt;"><b>SAP</b></h3>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ">
      <div class="form-group">
        <label for="id_cliente">*ID CLIENTE</label>
        <input class="form-control" id="id_cliente" name="id_cliente" type="number" placeholder="Escriba el ID del cliente" pattern="[0-9.]{,10}" required></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ">
      <div class="form-group">
        <label for="clearing">*Clearing Folio</label>
        <input class="form-control" id="clearing" name="clearing" placeholder="Clearing" pattern="[a-zA-Z0-9]{1,40}" required></input>
      </div>
    </div>
  </div>

  <h3 style="font-size: 16pt;"><b>DZ (Pago de bancos)</b></h3>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
      <label for="DocDate">Document_date</label>
      <input type="date" class="form-control" id="DocDate" name="DocDate" required></input>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
      <label for="fechap">FechaP</label>
      <input type="date" class="form-control" id="fechap" name="fechap" required></input>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <label for="MonedaP">Moneda</label>
      <div class="form-group">
        <select class="form-control" id="MonedaP" name="MonedaP">
          <option value="MXN" selected>MXN(Peso Mexicano)</option>
          <option value="USD">USD(Dólar Americano)</option>
        </select>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <label for="Parc">*Parcialidades</label>
      <input class="form-control" id="Parc" name="Parc" type="text" placeholder="Escriba su parcialidad" required></input>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="TipoCambio">*Tipo de cambio</label>
        <input class="form-control" type="number" step="0.0001" id="TipoCambio" name="TipoCambio" placeholder="Esciba el tipo de cambio" pattern="[0-9.]" required></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpSaldoAnt">*ImpSaldoAnt (monto)</label>
        <input class="form-control" type="number" step="0.0000000001" id="ImpSaldoAnt" name="ImpSaldoAnt" placeholder="Escriba el IpmSaldoAnterior" pattern="[0-9.]" required></input>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpPagado">*ImpPago (monto mxn)</label>
        <input class="form-control" type="number" step="0.0000000001" id="ImpPagado" name="ImpPagado" min="0.0000000000" max="9999999.9999999999" placeholder="Escriba el ImpPago 0.00" required></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpSaldoInsoluto">*ImpSaldoInsoluto</label>
        <input class="form-control" type="number" step="0.0000000001" id="ImpSaldoInsoluto" name="ImpSaldoInsoluto" pattern="[0-9.]" placeholder="Escriba el ImpPagoInsoluto" required></input>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Reference">*Reference</label>
        <input class="form-control" id="Reference" name="Reference" maxlength="25" placeholder="Reference" required></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Assignment">*Asignment</label>
        <input class="form-control" id="Assignment" name="Assignment" maxlength="25" placeholder="Assignment" required></input>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Numregdtrib">Numregdtrib</label>
        <input class="form-control" id="Numregdtrib" name="Numregdtrib" type="number" placeholder="Escriba su número de regimen tributario"></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Tax">Tax</label>
        <input class="form-control" id="Tax" name="Tax" placeholder="1" value=1 required></input>
      </div>
    </div>
  </div>

  <br />

  <!-- <center>
    <button type="submit" for="formulario" class="btn clonar" style="background: #01BB7D; color: white;" type="button">Agregar <b>Dtipo</b></button>
  </center> -->

  <br />
  <h3 style="font-size: 16pt;"><b>Dtipos</b></h3>
  <hr class="underline">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
      <div class="form-group">
        <label for="dtipoh1">Dtipo</label>
        <select class="form-control" id="dtipoh1" name="dtipoh1">
          <option value="DZ" selected>DZ</option>
          <option value="DC">DC</option>
          <option value="AB">AB</option>
          <option value="RV">RV</option>
          <option value="RW">RW</option>
        </select>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2  col-lg-2">
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6  col-lg-6">
      <div class="form-group">
        <label for="FolioFacturah1">*folio_factura</label>
        <input class="form-control" id="FolioFacturah1" name="FolioFacturah1" pattern="[0-9#]" placeholder="Escriba el folio de la factura"></input>
      </div>
    </div>
  </div>


  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-6">
      <label for="DocDateh1">Document_date</label>
      <input type="date" class="form-control" id="DocDateh1" name="DocDateh1"></input>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-6">
      <label for="FechaPh1">FechaP</label>
      <input type="date" class="form-control" id="FechaPh1" name="FechaPh1"></input>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <label for="MonedaPh1">Moneda</label>
      <div class="form-group">
        <select class="form-control" id="MonedaPh1" name="MonedaPh1">
          <option value="MXN" selected>MXN(Peso Mexicano)</option>
          <option value="USD">USD(Dólar Americano)</option>
        </select>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <label for="Parch1">*Parcialidades</label>
      <input class="form-control" id="Parch1" name="Parch1" type="text" pattern="[a-zA-Z0-9.-/ ]" placeholder="Clearing"></input>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="TipoCambioh1">*Tipo de cambio</label>
        <input class="form-control" id="TipoCambioh1" name="TipoCambioh1" placeholder="Escriba el tipo de cambio" pattern="[0-9.]"></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpSaldoAnth1">*Monto</label>
        <input class="form-control" type="number" id="ImpSaldoAnth1" name="ImpSaldoAnth1" placeholder="Escriba el ImpSaldoAnterior" pattern="[0-9.]"></input>
      </div>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpPagadoh1">*MontoMXN</label>
        <input class="form-control" type="number" id="ImpPagadoh1" name="ImpPagadoh1" pattern="[0-9.]" placeholder="Escriba el ImpPago"></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="ImpSaldoInsh1">*ImpSaldoInsoluto</label>
        <input class="form-control" type="number" id="ImpSaldoInsh1" name="ImpSaldoInsh1" pattern="[0-9.]" placeholder="Escriba el ImpPagoInsoluto con tipo de moneda declarado en el dtipo"></input>
      </div>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Referenceh1">*Reference</label>
        <input class="form-control" id="Referenceh1" name="Referenceh1" pattern="[a-zA-Z0-9]" placeholder="Reference"></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Assignmenth1">*Asignment</label>
        <input class="form-control" pattern="[a-zA-Z0-9#]" id="Assignmenth1" name="Assignmenth1" placeholder="Reference"></input>
      </div>
    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Numregh1">Numregdtrib</label>
        <input class="form-control" id="Numregh1" name="Numregh1" placeholder="Escriba su número de regimen tributario"></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="Taxh1">Tax</label>
        <input class="form-control" id="Taxh1" name="Taxh1" placeholder="1" value=1></input>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="form-group">
        <label for="CfdiRel">CFDI Relacionado</label>
        <select class="form-control" id="CfdiRel" name="CfdiRel" required>
          <!-- <option selected>Catálogo c_MetodoPago</option> -->
          <option value="0" selected>Ningun CFDI relacionado</option>
          <option value="1">01 	Nota de crédito de los documentos relacionados</option>
          <option value="2">02 	Nota de débito de los documentos relacionados</option>
          <option value="3">03 	Devolución de mercancía sobre facturas o traslados previos</option>
          <option value="4">04 	Sustitución de los CFDI</option>
          <option value="5">05 	Traslados de mercancías facturados previamente</option>
          <option value="6">06 	Factura generada por los traslados previos</option>
          <option value="7">07		CFDI por aplicación de anticipo</option>
        </select>
      </div>
    </div>

  </div>
  <br /> <br />
  <!-- <button type="button" class="btn btn-danger" style="float: right;" onclick="$(this).parents('.items').remove()">Eliminar Dtipo <small><i class="far fa-trash-alt"></i></small></button> -->
  <!-- <button onclick="$(this).parents('.items').remove()" class="remove-btn btn btn-danger">Eliminar</button> -->

  <center>
    <button type="submit" for="formulario" class="btn clonar" style="background: #01BB7D; color: white;">Agregar <b>Dtipo</b></button>
    <br /><br />
    <button class="btn btn-secondary " type="submit" for="formulario" style="background: #009FE5; color: white; ">&nbsp;&nbsp;&nbsp;Integrar&nbsp;&nbsp;&nbsp;</button>
    <button class="btn btn-primary " type="button" style="background: #DD535A" data-toggle="modal" data-target=".Cancel">&nbsp;&nbsp;&nbsp;&nbsp;Borrar&nbsp;&nbsp;&nbsp;&nbsp;</button>
  </center>
  </form>
</div>

<script>
  $('.clonar').click(function() {
    // // Clona el .input-group
    // var $clone = $('#formulario2 .input-group').first().clone();

    // // Borra los valores de los inputs clonados
    // $clone.find(':input').each(function() {
    //   if ($(this).is('select')) {
    //     this.selectedIndex = 0;
    //   } else {
    //     this.value = '';
    //   }
    // });

    // // Agrega lo clonado al final del #formulario
    // $clone.appendTo('#formulario2');
    if ($("#id_cliente").val() == '' || $("#clearing").val() == '') {

    } else {
      $("#mostrardtipo").modal('show');
    }
  });
</script>


@endsection
<!-- Modal para guardar un dtipo -->
<div class="modal fade mostrardtipo" tabindex="-1" id="mostrardtipo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center"><b>Integrar</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </h3>
        </button>
      </div>
      <div class="modal-body text-center">

        <img src="{{asset('assets/img/singo1.png')}}">
        <h4>Se guardarán los datos que están en la sección dtipo, para poder agregar uno nuevo</h4>
        <h4>¿Estas seguro que deseas guardar los datos?</h4>
      </div>
      <div class="modal-footer text-center">
        <form id="formulario" action="javascript:mandarDtipo()" method="post">
          {{csrf_field()}}
          <button type="submit" class="btn btn-primary" for="formulario2" type="submit">Aceptar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modales -->
<div class="modal fade Integrar1" tabindex="-1" id="Integrar1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center"><b>Integrar</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </h3>
        </button>
      </div>
      <div class="modal-body text-center">

        <img src="{{asset('assets/img/singo1.png')}}">
        <h4>¿Estas seguro que deseas integrar los datos?</h4>
        <p>Si agrego todos los dtipo que necesita, entonces de clic en aceptar</p>
      </div>
      <div class="modal-footer text-center">
        <form id="formulario" action="javascript:mandarFormulario();" method="post">
          {{csrf_field()}}
          <button type="submit" class="btn btn-primary" for="formulario" type="submit">Aceptar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación para mandar a integrar -->
<div class="modal fade Cancel" id="Cancel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center"><b>Cancelar</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </h3>
        </button>
      </div>
      <div class="modal-body text-center">

        <img src="{{asset('assets/img/signo2.png')}}">
        <h4>Se borrarán todos tus campos actuales</h4>
      </div>
      <div class="modal-footer text-center">
        <button type="button" class="btn btn-primary" onclick="limpiar()">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
<!-- Fin modal -->
<!-- Modal cargando -->
<div class="modal fade" id="modal-cargando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h3><strong>Cargando</strong></h3>
        <img src="{{asset('assets/img/cargando-loading-039.gif')}}" width="500">
        <p>Espera un momento. Se están cargando tus archivos.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-6" align="left">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal de error -->
<div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Hubo un error al subir tus datos.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-6" align="left">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal de error de datos dtipo -->
<div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Los datos del formulario que tienen que ver con dtipo no están completos.</p>
        <p>Por favor revise nuevamente los datos e intentelo de nuevo.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-4" align="left">
          </div>
          <div class="col-xs-4" align="center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
          <div class="col-xs-4" align="left">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
<!-- Modal -->
<div class="modal fade" id="modal-dtipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Exito</strong></h3>
          <p>Se han subido tus datos correctamente.</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-4" align="left">
          </div>
          <div class="col-xs-4" align="center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
          <div class="col-xs-4" align="left">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
<!-- Modal -->
<div class="modal fade" id="modal-errorintegracion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Lo sentimos, los datos no pudieron hacer match o el clearing document ya ha sido integrado anteriormente.</p>
        <p>Por favor intentelo con otro archivo que no se haya integrado anteriormente</p>
        <p>O revise que los campos dtipo DZ en ambas hojas del excel sean del mismo tipo de moneda</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-6" align="left">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->

<!-- Modal -->
<div class="modal fade" id="modal-exitoCasiFinal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <h3 class="modal-title text-center" style="padding-top: 10px;"><strong>Exito</strong></h3>
      <hr class="underline" />
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/singo1.png')}}">
        <p>Se han subido tus datos correctamente.</p>
        <p>Por favor, selecciona el número de decimales que se <br /> incluirán en el txt, en los datos de montos</p>
        <br />
        <form id="info" action="javascript:generarElTxt();" method="post">
          {{csrf_field()}}

          <select class="form-control col-md-11" id="decimales" name="decimales">
            <!-- <option selected>Catálogo c_MetodoPago</option> -->
            <option value="2" selected>2 decimales</option>
            <option value="3">3 decimales</option>
            <option value="4">4 decimales</option>
            <option value="5">5 decimales</option>
            <option value="6">6 decimales</option>
            <option value="7">7 decimales</option>
            <option value="8">8 decimales</option>
            <option value="9">9 decimales</option>
            <option value="10">10 decimales</option>
          </select>
          <p>&nbsp;</p>

          <button type="submit" class="btn btn-primary">Generar txt.</button>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-errortxt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Hubo un error al crear los archivos.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-6" align="left">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Exito</strong></h3>
          <p>Se han subido tus datos correctamente.</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-4" align="left">
          </div>
          <div class="col-xs-4" align="center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
          <div class="col-xs-4" align="left">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
<!-- Modal -->
<div class="modal fade" id="modal-no-carga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>No hay nada que enviar.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-4" align="left">
          </div>
          <div class="col-xs-4" align="center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
          <div class="col-xs-4" align="left">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
<!-- Modal de error -->
<div class="modal fade" id="modal-errorBorrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Hubo un error al borrar los datos, por favor actualiza la página.</p>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-6" align="left">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->







<script type="text/javascript">
  // Funcion para limpiar todos los campos del formulario
  function limpiar() {
    console.log("detecta función de reseteo");
    document.getElementById("id_cliente").value="";
    document.getElementById("clearing").value = "";
    document.getElementById("DocDate").value = "";
    document.getElementById("fechap").value = "";
    document.getElementById("Parc").value = "";
    document.getElementById("TipoCambio").value = "";
    document.getElementById("ImpSaldoAnt").value = "";
    document.getElementById("ImpPagado").value = "";
    document.getElementById("ImpSaldoInsoluto").value = "";
    document.getElementById("Reference").value = "";
    document.getElementById("Assignment").value = "";
    document.getElementById("Numregdtrib").value = "";
    document.getElementById("Tax").value = 1;
    document.getElementById("FolioFacturah1").value = "";
    document.getElementById("DocDateh1").value = "";
    document.getElementById("FechaPh1").value = "";
    document.getElementById("Parch1").value = "";
    document.getElementById("TipoCambioh1").value = "";
    document.getElementById("ImpSaldoAnth1").value = "";
    document.getElementById("ImpPagadoh1").value = "";
    document.getElementById("ImpSaldoInsh1").value = "";
    document.getElementById("Referenceh1").value = "";
    document.getElementById("Assignmenth1").value = "";
    document.getElementById("Numregh1").value = "";
    document.getElementById("Taxh1").value = 1;
    actualizarBD();
  }
  function actualizarBD() {
    var form = new FormData(document.getElementById('formulario'));
    $.ajax({
      url: 'borrarForm',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#Cancel").modal('hide');
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        console.log(data)
        //console.log(data.mensaje.FOLIO)
        if (data.respuesta == 1) {
          $("#modal-cargando").modal('hide');
        } else {
          $("#modal-errorBorrar").modal('show');
        }
      },
      error: function() {
        console.log("Error al borrar los datos de temporal_sap o al crear nuevo excel en BD")
        $("#modal-cargando").modal('hide');
        $("#modal-errorBorrar").modal('show');
        $("#subir").attr("disabled", false);
      }
    });
  }
  function cargarFormulario() {
    $("#Integrar1").modal('show');
  }

  function mandarDtipo() {
    $("#mostrardtipo").modal('hide');
    var form = new FormData(document.getElementById('formulario'));
    form.append("idcliente", $("#id_cliente").val());
    form.append("folio", $("#clearing").val());
    form.append("dtipo", $("#dtipoh1").val());
    form.append("folio_factura", $("#FolioFacturah1").val());
    form.append("doc_date", $("#DocDateh1").val());
    form.append("fechap", $("#FechaPh1").val());
    form.append("moneda", $("#MonedaPh1").val());
    form.append("parcialidad", $("#Parch1").val());
    form.append("tipo_cambio", $("#TipoCambioh1").val());
    form.append("sal_ant", $("#ImpSaldoAnth1").val());
    form.append("sal_pagado", $("#ImpPagadoh1").val());
    form.append("sal_ins", $("#ImpSaldoInsh1").val());
    form.append("reference", $("#Referenceh1").val());
    form.append("assignment", $("#Assignmenth1").val());
    form.append("numreg", $("#Numregh1").val());
    form.append("tax", $("#Taxh1").val());
    form.append("cfdi_rel", $("#CfdiRel").val());

    $.ajax({
      url: 'crearExcel',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#Cancel").modal('hide');
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        console.log(data)
        //console.log(data.mensaje.FOLIO)
        if (data.respuesta == 1) {
          $.ajax({
            url: 'cargarSAPEsp',
            type: 'post',
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function() {
              $("#modal-cargando").modal('show');
            },
            success: function(data) {
              console.log(data)
              //console.log(data.mensaje.FOLIO)
              if (data.respuesta == 1) {
                $("#mostrardtipo").modal('hide');
                $("#modal-cargando").modal('hide');
                $("#modal-dtipo").modal('show');
                console.log("Si se guardaron los datos chido");
                // guardarTablasChidas();
                document.getElementById("FolioFacturah1").value = "";
                document.getElementById("DocDateh1").value = "";
                document.getElementById("FechaPh1").value = "";
                document.getElementById("Parch1").value = "";
                document.getElementById("TipoCambioh1").value = "";
                document.getElementById("ImpSaldoAnth1").value = "";
                document.getElementById("ImpPagadoh1").value = "";
                document.getElementById("ImpSaldoInsh1").value = "";
                document.getElementById("Referenceh1").value = "";
                document.getElementById("Assignmenth1").value = "";
                document.getElementById("Numregh1").value = "";
                document.getElementById("Taxh1").value = 1;
              } else {
                if (data.respuesta == 2) {
                  $("#mostrardtipo").modal('hide');
                  $("#modal-cargando").modal('hide');
                  $("#modal-no-archivo").modal('show');
                } else {
                  $("#modal-no-archivo").modal('show');
                }
              }
              $("#modal-cargando").modal('hide');
            },
            error: function() {
              console.log("Error al guardar la prueba")
              $("#modal-cargando").modal('hide');
              $("#modal-no-archivo").modal('show');
              $("#subir").attr("disabled", false);
            }
          });
        } else {
          $("#modal-errorBorrar").modal('show');
        }
      },
      error: function() {
        console.log("Error al borrar los datos de temporal_sap o al crear nuevo excel en BD")
        $("#modal-cargando").modal('hide');
        $("#modal-errorBorrar").modal('show');
        $("#subir").attr("disabled", false);
      }
    });
    
  }

  function mandarFormulario() {

    var form = new FormData(document.getElementById('formulario'));
    form.append("idcliente", $("#id_cliente").val());
    form.append("folio", $("#clearing").val());
    form.append("doc_date", $("#DocDate").val());
    form.append("fechap", $("#fechap").val());
    form.append("moneda", $("#MonedaP").val());
    form.append("parcialidad", $("#Parc").val());
    form.append("tipo_cambio", $("#TipoCambio").val());
    form.append("sal_ant", $("#ImpSaldoAnt").val());
    form.append("sal_pagado", $("#ImpPagado").val());
    form.append("sal_ins", $("#ImpSaldoInsoluto").val());
    form.append("reference", $("#Reference").val());
    form.append("assignment", $("#Assignment").val());
    form.append("numreg", $("#Numregdtrib").val());
    form.append("tax", $("#Tax").val());

    $.ajax({
      url: 'cargarSAPForm',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#Integrar1").modal('hide');
        $("#modal-cargando").modal('show');
        $("#subir").attr("disabled", true);
        $('#cuerpo').html("");
      },
      success: function(data) {
        console.log(data)
        //console.log(data.mensaje.FOLIO)
        if (data.respuesta == 1) {
          //console.log("Si se guardaron los datos chido");
          guardarTablasChidas();
        } else {
          if (data.respuesta == 2) {
            $("#modal-no-archivo").modal('show');
          } else {
            $("#modal-no-archivo").modal('show');
          }
        }
        // $("#modal-cargando").modal('hide');
        // $("#subir").attr("disabled", false);
      },
      error: function() {
        console.log("Error al guardar la prueba")
        $("#modal-cargando").modal('hide');
        $("#modal-no-archivo").modal('show');
        $("#subir").attr("disabled", false);
      }
    });
  }

  function guardarTablasChidas() {
    console.log("Entra en la segunda función")
    var form = new FormData(document.getElementById('formulario'));
    var contenido = "";
    $.ajax({
      url: 'guardarSAPForm',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        console.log(data)
        if (data.respuesta == "2" || data.respuesta == 2) {
          $("#modal-no-carga").modal("show");
          console.log("que es: " + data.quees);
          console.log("registros: " + data.sihay);
          console.log("actaliza tabla creditlocaldr: " + data.unoup);
          console.log("actaliza tabla facturas_liquidadas: " + data.dosup);
          console.log("actaliza tabla parcialidades: " + data.tresup);
          console.log("actaliza tabla factura: " + data.cuatroup);
          console.log("actaliza tabla parcialidades: " + data.cincoup);
          console.log("actaliza tabla parcialidades con diferente moneda: " + data.seisup);
          console.log("actaliza tabla Bancos_Sap: " + data.sieteup);
          console.log("actaliza tabla Excel_Sap: " + data.ochoup);
          console.log("obtiene el correo de quien se logueo: " + data.user);
          console.log("mensaje: " + data.mensajito);
        } else {
          $("#modal-cargando").modal('show');
          // $("#modal-exito").modal("show");
          console.log("registros: " + data.sihay);
          console.log("se elimino la tabla temporal_SAP: " + data.elimina1);
          console.log("actaliza tabla creditlocaldr: " + data.unoup);
          console.log("actaliza tabla facturas_liquidadas: " + data.dosup);
          console.log("actaliza tabla parcialidades: " + data.tresup);
          console.log("actaliza tabla factura: " + data.cuatroup);
          console.log("actaliza tabla parcialidades: " + data.cincoup);
          console.log("actaliza tabla parcialidades con diferente moneda: " + data.seisup);
          console.log("actaliza tabla Bancos_Sap: " + data.sieteup);
          console.log("actaliza tabla Excel_Sap: " + data.ochoup);
          console.log("obtiene el correo de quien se logueo: " + data.user);
          console.log("mensaje: " + data.mensajito);
          console.log("id de excel ")
          integrar();
        }
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-error").modal("show");
        console.log("Error al guardar la prueba posible error en el archivo ValidacionSapcontroler@guardarDatosForm");
        //console.log("no se conecta a la base de datos puede ser los drivers");
      }
    });
  }

  function abrirModal() {
    $("#modal-exitoCasiFinal").modal("show");
  }

  function integrar() {
    //window.location.href = "integracion";
    var form = new FormData(document.getElementById('formulario'));
    form.append("layout", $("#layout").val());
    form.append("FormatoDePagoP", $("#FormatoDePagoP").val());
    $.ajax({
      url: 'integrandoEsp',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        $("#modal-cargando").modal('hide');
        if (data.respuesta == "1" || data.respuesta == 1) {
          if (data.numero == 0 || data.numero == "0") {
            $("#modal-errorintegracion").modal("show");
          } else {
            $("#modal-exitoCasiFinal").modal("show");
          }
        } else {
          $("#modal-errorintegracion").modal("show");
        }
        // window.location.href = "integracion";
        console.log(data);
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-error").modal('show');
      }
    });
  }

  function generarElTxt() {
    $("#modal-exitoCasiFinal").modal('hide');
    console.log("Si detecta la función de creación de txt");
    var form = new FormData(document.getElementById('formulario'));
    form.append("layout", $("#layout").val());
    form.append("FormatoDePagoP", $("#FormatoDePagoP").val());
    form.append("MetodoDePagoDR", $("#MetodoDePagoDR").val());
    form.append("fechap", $("#fechap").val());
    form.append("decimales", $("#info").serialize());
    $.ajax({
      url: 'generarTxtCorrectosEsp',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        if (data.respuesta == "si") {
          limpiar();
          finalizarProceso();
        } else {
          $("#modal-errortxt").modal("show");
        }
        console.log(data);
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-errortxt").modal('show');
      }
    });
  }
  function finalizarProceso() {
    var formulario = new FormData(document.getElementById("formulario"));
    $.ajax({
      url: "finalizarProceso",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        $("#modal-cargando").modal('hide');
        $("#modal-exito").modal("show");
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-errortxt").modal('show');
      }
    })
  }
</script>