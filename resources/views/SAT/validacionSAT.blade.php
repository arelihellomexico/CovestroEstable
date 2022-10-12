@extends('Plantilla.plantilla')
@if(Session::get('tipo') != 1)
@section('title','Validacion archivos SAP')
@section('usuario','Usuario SAP')
@section('validacionMenu','no-mostrar')
@section('tesoreriaMenu','no-mostrar')
@section('creditoMenu','no-mostrar')
@section('concentradoMenu','no-mostrar')
@section('clientesMenu','no-mostrar')
@section('covestroMenu','no-mostrar')
@else
@section('title','Usuario Administrador')
@endif
@section('sapMenu','sap-active')
@section('contenido')

<!--Encabezado-->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left leadv"><i class="icon-cloud-upload"></i>&nbsp;&nbsp;Validación de archivos</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">SAP<img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form class="" id="formulario" action="javascript:cargarTabla()" method="post">
      {{csrf_field()}}
      <div class="form-group">
        <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2">
          <label for="layout" class="text-muted control-label layout-select"><small>Selecciona un Layout:</small></label>
        </div>
        <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2">
          <select id="layout" name="layout" class="form-control layo">
            @foreach($layout as $l)
            <option value="{{$l->id_ls}}">{{$l->nombre}}</option>
            @endforeach
          </select>
        </div>
      </div>
  </div>
</div>
<!-- Fin Barra navegacion superior -->
<br>
<!-- Drop area -->

<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label for="file" class="br_dropzone layo2">
      <input type="file" id="file" name="excel[]" onchange="if(this.files.length == 1){this.form.fileName.value = this.files[0].name;}if(this.files.length == 2){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name}if(this.files.length == 3){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name}if(this.files.length == 4){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name}if(this.files.length == 5){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name+'-'+this.files[4].name} if(this.files.length > 5){this.form.fileName.value = 'No puedes subir mas de 5 archivos'}" required multiple>
      <input type="text" id="fileName" name="fileName" placeholder="Arrastra tu archivo aqu&iacute;" readonly>
    </label>
  </div>
</div>
<div class="col-12 col-xs-12 col-sm-12 col-md-1 col-lg-1 col-md-offset-5 col-lg-offset-5">
  <button type="submit" name="cargarArchivo" class="button btn-blue"><small>Subir</small></button>
</div>
</form>
<!-- fin drop area -->

<!-- Resultados de validacion texto -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Resultados de validación<img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin resultado de validacion texto -->

<!-- Tabla de resultados -->
<div class="row" id="contenedor">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">
    <table class="display AllDataTable table table-bordered table-hover space table-striped" id="tableSap">
      <thead>
        <tr>
          <th class="text-muted text-center txt-thead"><small>Folio</small></th>
          <th class="text-muted text-center txt-thead"><small>Régimen Fiscal</small></th>
          <th class="text-muted text-center txt-thead"><small>RFC del emisor</small></th>
          <th class="text-muted text-center txt-thead"><small>Nombre del emisor</small></th>
          <th class="text-muted text-center txt-thead"><small>Dirección del emisor</small></th>
          <th class="text-muted text-center txt-thead"><small>RFC del receptor</small></th>
          <th class="text-muted text-center txt-thead"><small>Nombre del receptor</small></th>
          <th class="text-muted text-center txt-thead"><small>Dirección del emisor</small></th>
          <th class="text-muted text-center txt-thead"><small>Número de Pago</small></th>
          <th class="text-muted text-center txt-thead"><small>Moneda de Pago</small></th>
          <th class="text-muted text-center txt-thead"><small>Tipo de Cambio</small></th>
          <th class="text-muted text-center txt-thead"><small>Monto de Pago</small></th>
          <th class="text-muted text-center txt-thead"><small>Residencia fiscal</small></th>
          <th class="text-muted text-center txt-thead"><small>Número de registro tributario</small></th>
          <th class="text-muted text-center txt-thead"><small>Lugar de expedición</small></th>
          <th class="text-muted text-center txt-thead"><small>Folio de la factura</small></th>
          <th class="text-muted text-center txt-thead"><small>ID del documento</small></th>
          <th class="text-muted text-center txt-thead"><small>Parcialidades</small></th>
          <th class="text-muted text-center txt-thead"><small>Usocfdi</small></th>
          <th class="text-muted text-center txt-thead"><small>Regimen</small></th>
          <th class="text-muted text-center txt-thead"><small>Tasa iva</small></th>
          <th class="text-muted text-center txt-thead"><small>Tasa retencion</small></th>
          <th class="text-muted text-center txt-thead"><small>Errores</small></th>
        </tr>
      </thead>
      <tbody id="cuerpo">
      </tbody>
    </table>
  </div>
</div>
<br>
<!-- Fin de tabla de resultados -->
<!-- Boton Enviar -->
<div class="col-4 col-sm-4 col-md-2 col-lg-2 col-md-offset-3 col-lg-offset-3">
  <form action="javascript:guardarDatosVerificar();" method="post" id="cosas2">
    <!--Este es tu form solo descomenta-->
    {{csrf_field()}}
    <input type="hidden" name="nombre_excel" id="archivo1">
    <button type="submit" class="btn btn-green"><small>Enviar a integración</small></button>
  </form>
</div>
<!-- Fin boton enviar -->

<!-- Boton volver a cargar -->
<div class="col-4 col-sm-4 col-md-2 col-lg-2 ">
  <form action="javascript:eliminarDatos();" method="post" id="cosas">
    <!--Este es tu form solo descomenta-->
    {{csrf_field()}}
    <button type="submit" class="btn btn-pink"><small>Volver a cargar</small></button>
  </form>
</div>
<!-- fin boton volver a cargar -->
@endsection

<!-- Modal -->
<div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Archivo Incompleto</strong></h3>
          <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido.</p>
          <p>
            <div id="mens"></div>
          </p>
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
<div class="modal fade" id="modal-verifica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Datos incorrectos</strong></h3>
          <p>Algunos datos que ha cargado son incorrectos. ¿Desea de todos modos enviarlos a integración de archivos? Es probable que esto genere un error.</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form id="cosas3" action="javascript:guardarDatos();">
              {{csrf_field()}}
              <input type="hidden" name="nombre_excel" id="archivo2">
              <button type="submit" class="btn btn-primary">Si, enviar de todos modos.</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal -->
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
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>No hay nada que enviar.</p>
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
<div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>No hay ningun archivo para cargar.</p>
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
<div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>Hubo un error al subir tus datos.</p>
        </center>
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
<div class="modal fade" id="modal-cargando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <h3><strong>Cargando</strong></h3>
          <img src="{{asset('assets/img/cargando-loading-039.gif')}}" width="500">
          <p>Espera un momento. Se están cargando tus archivos.</p>
        </center>
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

<!-- Script -->
<script type="text/javascript" src="{{asset('assets/js/dropmenu.js')}}"></script>
<script type="text/javascript">
  var may = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  var min = "abcdefghijklmnñopqrstuvwxyz";
  var num = "1234567890.,^`+-*/_=¨´~{}[]:;$%&()#@";
  var rfccaracter = ".,^`+-*/_=¨´~{}[]:;$%&()#@ ";
  var letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  var cantidad = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ^`+-*/_=¨´~{}[]:;$%&()#@";
  var incidencias = false;

  function cargarTabla() {
    var form = new FormData(document.getElementById('formulario'));
    var contenido = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">' +
      '<table class="display AllDataTable table table-bordered table-hover space table-striped" id="tableSap">' +
      '<thead>' +
      '<tr>' +
      '<th class="text-muted text-center txt-thead"><small>Folio</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Régimen Fiscal</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>RFC del emisor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Nombre del emisor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Dirección del emisor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>RFC del receptor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Nombre del receptor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Dirección del emisor</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Número de Pago</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Moneda de Pago</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Tipo de Cambio</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Monto de Pago</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Residencia fiscal</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Número de registro tributario</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Lugar de expedición</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Folio de la factura</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>ID del documento</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Parcialidades</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Usocfdi</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Regimen</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Tasa iva</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Tasa retencion</small></th>' +
      '<th class="text-muted text-center txt-thead"><small>Errores</small></th>' +
      '</tr>' +
      '</thead>' +
      '<tbody id="cuerpo">';
    var reporte = "";
    if ($("#layout").val() != "") {
      console.log("El valor de archivo es " + $("#file").val())
      if ($("#file").val() != null && $("#file").val() != "") {
        $("#archivo1").val($("#file").val());
        $("#archivo2").val($("#file").val());
        $.ajax({
          url: 'cargarSAP',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $("#modal-cargando").modal('show');
            $("#subir").attr("disabled", true);
            $('#cuerpo').html("");
          },
          success: function(data) {
            if (data.length < 1) {
              console.log("No habia datos en tu archivo de excel.");
            } else {
              console.log('Entre aqui a sucessssss ')
              if (data.respuesta == 2) {
                dm = data.mensaje;
                cod_error = dm.split(":");
                $("#mens").html("");
                if (cod_error[0] == "Undefined index") {
                  $("#mens").html("No se reconoce la columna " + cod_error[1] + " en las hojas de tu archivo de excel con nombre " + data.archivo + ".");
                } else {
                  $("#mens").html(data.mensaje + ". Este error se muestra en el archivo " + data.archivo + ".");
                }
                $("#modal-cargando").modal('hide');
                $("#modal-falta").modal('show');
              } else {

                //datableble.destroy();
                for (var i = 0; i < data.length; i++) {
                  reporte = "";
                  contenido += '<tr>';
                  if (data[i].FOLIO != null && soloNumeros(data[i].FOLIO) == false) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].FOLIO + '</td>';
                  } else {
                    if (data[i].FOLIO != null) {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].FOLIO + '</td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    }
                    reporte += "-> El clearing document no puede quedar vacio. Debe tener 14 caracteres.<br>";
                    incidencias = true;

                  }
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].REGIMEN + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].RFC_E + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].NOMBRE_E + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].DIRECCION_E + '</td>';
                  if (data[i].RFC_R.indexOf("El cliente") == -1 || data[i].RFC_R.indexOf("Este pago") == -1) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].RFC_R + '</td>';
                  } else {
                    contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].RFC_R + '</td>';
                    reporte += "-> El cliente no existe.<br>";
                    incidencias = true;
                  }
                  if (data[i].NOMBRE_R.indexOf("El cliente") == -1 || data[i].NOMBRE_R.indexOf("Este pago") == -1) {
                    if (data[i].NOMBRE_R == null) {
                      contenido += '<td class="text-center text-muted txt-tbody"></td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody">' + data[i].NOMBRE_R + '</td>';
                    }
                  } else {
                    contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].NOMBRE_R + '</td>';
                    reporte += "-> El cliente no existe.<br>";
                    incidencias = true;
                  }
                  if (data[i].DIRECCION_R == null || (data[i].DIRECCION_R.indexOf("El cliente") == -1 || data[i].DIRECCION_R.indexOf("Este pago") == -1)) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].DIRECCION_R + '</td>';
                  } else {
                    contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].DIRECCION_R + '</td>';
                    incidencias = true;
                  }
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].NUMPAGO + '</td>';
                  if (data[i].MONEDAPAGO != null && data[i].MONEDAPAGO.length == 3 && soloLetras(data[i].MONEDAPAGO) == false) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].MONEDAPAGO + '</td>';
                  } else {
                    if (data[i].MONEDAPAGO != null) {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].MONEDAPAGO + '</td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';

                    }
                    reporte += "-> La moneda de Pago NO puede estar vacía, debe ser una palabra de 3 letras.<br>";
                    incidencias = true;
                  }
                  if (data[i].TIPOCAMBIOP != null && monto(data[i].TIPOCAMBIOP) == false) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].TIPOCAMBIOP + '</td>';
                  } else {
                    if (data[i].TIPOCAMBIOP != null) {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].TIPOCAMBIOP + '</td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    }
                    reporte += "-> El monto de pago NO puede estar vacío. Debe ser solo un número, con o sin decimales (17.63, 17.00, 17)<br>";
                    incidencias = true;
                  }
                  if (data[i].MONTOPAGO != null && monto(data[i].MONTOPAGO) == false) {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].MONTOPAGO + '</td>';
                  } else {
                    if (data[i].MONTOPAGO != null) {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].MONTOPAGO + '</td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    }
                    reporte += "-> El monto de pago NO puede estar vacío. Debe ser solo un número, con o sin decimales (17.63, 17.00, 17)<br>";
                    incidencias = true;
                  }
                  if (data[i].RESIDENCIAFISCAL != null && data[i].RESIDENCIAFISCAL != "") {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].RESIDENCIAFISCAL + '</td>';
                  } else {
                    contenido += '<td class="text-center text-muted txt-tbody"></td>';
                  }
                  if (data[i].RESIDENCIAFISCAL != null && data[i].RESIDENCIAFISCAL != "") {
                    if (data[i].NUMREGIDTRIB != null && data[i].NUMREGIDTRIB != "") {
                      contenido += '<td class="text-center text-muted txt-tbody">' + data[i].NUMREGIDTRIB + '</td>';
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                      reporte += "-> No puede ir vacío el campo de Número de registro tributario, si la residencia fiscal es diferente a MEX.<br>";
                      incidencias = true;
                    }
                  } else {
                    if (data[i].NUMREGIDTRIB != null && data[i].NUMREGIDTRIB != "") {
                      contenido += '<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">' + data[i].NUMREGIDTRIB + '</td>';
                      reporte += "-> Si la residencia fiscal es MEX, el campo debe ir vacío.<br>";
                      incidencias = true;
                    } else {
                      contenido += '<td class="text-center text-muted txt-tbody"></td>';
                    }

                  }
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].LUGAREXPEDICION + '</td>';
                  if (data[i].TIPODOC == "DZ") {
                    if (data[i].FOLIOS == "" || data[i].FOLIOS == null) {
                      contenido += '<td class="text-center text-muted txt-tbody"></td>';
                    } else {
                      if (data[i].FOLIOS != null && (data[i].FOLIOS == "0" || data[i].FOLIOS == 0 || data[i].FOLIOS == "#")) {
                        contenido += '<td class="text-center text-muted txt-tbody"></td>';
                      } else {
                        contenido += '<td class="text-center text-muted txt-tbody">' + data[i].FOLIOS + '</td>';
                      }
                    }
                  } else {
                    if (data[i].FOLIOS != null && soloNumeros(data[i].FOLIOS) == false && data[i].FOLIOS.length == 7 && data[i].FOLIOS == data[i].ID_DOC) {
                      contenido += '<td class="text-center text-muted txt-tbody">' + data[i].FOLIOS + '</td>';
                    } else {
                      if (data[i].FOLIOS != null) {
                        contenido += '<td class="text-center text-muted txt-tbody">' + data[i].FOLIOS + '</td>';
                      } else {
                        contenido += '<td class="text-center text-muted txt-tbody"></td>';
                      }
                      //incidencias = true;
                    }
                  }
                  if (data[i].TIPODOC == "DZ") {
                    if (data[i].ID_DOC == "" || data[i].ID_DOC == null) {
                      contenido += '<td class="text-center text-muted txt-tbody"></td>';
                    } else {
                      if (data[i].ID_DOC != null && (data[i].ID_DOC == "0" || data[i].ID_DOC == 0 || data[i].ID_DOC == "#")) {
                        contenido += '<td class="text-center text-muted txt-tbody"></td>';
                      } else {
                        contenido += '<td class="text-center text-muted txt-tbody">' + data[i].ID_DOC + '</td>';
                      }
                    }
                  } else {
                    if (data[i].ID_DOC != null && soloNumeros(data[i].ID_DOC) == false && data[i].ID_DOC.length == 7 && data[i].ID_DOC == data[i].FOLIOS) {
                      contenido += '<td class="text-center text-muted txt-tbody">' + data[i].ID_DOC + '</td>';
                    } else {
                      if (data[i].ID_DOC != null && data[i].ID_DOC != "" && data[i].ID_DOC != 0 && data[i].ID_DOC != "0" && data[i].ID_DOC != "#") {
                        contenido += '<td class="text-center text-muted txt-tbody">' + data[i].ID_DOC + '</td>';
                      } else {
                        contenido += '<td class="text-center text-muted txt-tbody"></td>';
                      }
                      //incidencias = true;
                    }
                  }
                  if (data[i].PARCIAL != null && data[i].PARCIAL != "") {
                    contenido += '<td class="text-center text-muted txt-tbody">' + data[i].PARCIAL + '</td>';
                  } else {
                    contenido += '<td class="text-center text-muted txt-tbody"></td>';
                  }
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].USOCFDI + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].REGIMEN + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].TASAIVA + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + data[i].TASARETENCION + '</td>';
                  contenido += '<td class="text-center text-muted txt-tbody">' + reporte + '</td>';
                  contenido += '</tr>';
                }
              }

            }

            contenido += '</tbody></table>' +
              '<script type="text/javascript">' +
              '$("#tableSap").dataTable({' +
              '"bDestroy": true,' +
              '"ordering": false,' +
              'language:{' +
              '"sProcessing":     "Procesando...",' +
              '"sLengthMenu":     "Mostrar _MENU_ registros",' +
              '"sZeroRecords":    "No se encontraron resultados",' +
              '"sEmptyTable":     "Ningún dato disponible en esta tabla",' +
              '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",' +
              '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",' +
              '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",' +
              '"sInfoPostFix":    "",' +
              '"sSearch":         "Buscar:",' +
              '"sUrl":            "",' +
              '"sInfoThousands":  ",",' +
              '"sLoadingRecords": "Cargando...",' +
              '"oPaginate": {' +
              '"sFirst":    "Primero",' +
              '"sLast":     "Último",' +
              '"sNext":     "Siguiente",' +
              '"sPrevious": "Anterior"' +
              '},' +
              '"oAria": {' +
              '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",' +
              '"sSortDescending": ": Activar para ordenar la columna de manera descendente"' +
              '}' +
              '}' +
              '});' +
              '<\/script>' +
              '</div>';

            document.getElementById("contenedor").removeChild(document.getElementById("scroll"))
            $('#contenedor').append(contenido);
            /*datableble = $('#tableSap').dataTable({
              "paging":false,
              "ordering": false,
              "bFilter": false,
              "bDestroy": true,
              language:{
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "Mostrar _MENU_ registros",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
            }
            });*/
            $("#modal-cargando").modal('hide');
            $("#subir").attr("disabled", false);
          },
          error: function() {
            console.log("Error al guardar la prueba")
            $("#modal-cargando").modal('hide');
            $("#modal-falta").modal('show');
            $("#subir").attr("disabled", false);
          }
        });
      } else {
        $("#modal-no-archivo").modal("show");
      }
    } else {
      $("#modal-cargando").modal('hide');
      $("#modal-layout").modal("show");
      $("#subir").attr("disabled", false);
    }
  }

  function eliminarDatos() {
    var form = new FormData(document.getElementById('cosas'));
    var contenido = "";
    $.ajax({
      url: 'recargarSAP',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      success: function(data) {
        contenido += '<td class="text-muted" name="FRCCTAORD"></td>' +
          '<td class="text-muted" name="BANCOORDEXT"></td>' +
          '<td class="text-muted" name="CTAORD"></td>' +
          '<td class="text-muted" name="FORMAP"></td>' +
          '<td class="text-muted" name="MONEDAP"></td>' +
          '<td class="text-muted" name="MONTOP"></td>' +
          '<td class="text-muted" name="NUMEROPERP"></td>' +
          '<td class="text-muted" name="Complemento"></td>' +
          '<td class="text-muted" name="RFCCTABEN"></td>' +
          '<td class="text-muted" name="CATABEN"></td>' +
          '<td class="text-muted" name="RFCCTABEN"></td>' +
          '<td class="text-muted" name="CATABEN"></td>';

        $('#cuerpo').html("");
        $('#cuerpo').append(contenido);
        $('#file').val("");
        incidencias = false;
        $('#fileName').val("Arrastra tu archivo aquí")
      },
      error: function() {
        console.log("Error al guardar la prueba")
      }
    });
  }

  function guardarDatosVerificar() {
    console.log("entro a la funcion correctamente");
    var form = new FormData(document.getElementById('cosas3'));
    var contenido = "";
    if (incidencias == true) {
      $("#modal-verifica").modal("show");
      incidencias = false;
      console.log("Entre bien");
    } else {
      console.log("Entre bien");
      $.ajax({
        url: 'guardarSAP',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $("#modal-cargando").modal('show');
          console.log("Entre bien 2");
        },
        success: function(data) {
          contenido += '<td class="text-muted" name="FRCCTAORD"></td>' +
            '<td class="text-muted" name="BANCOORDEXT"></td>' +
            '<td class="text-muted" name="CTAORD"></td>' +
            '<td class="text-muted" name="FORMAP"></td>' +
            '<td class="text-muted" name="MONEDAP"></td>' +
            '<td class="text-muted" name="MONTOP"></td>' +
            '<td class="text-muted" name="NUMEROPERP"></td>' +
            '<td class="text-muted" name="Complemento"></td>' +
            '<td class="text-muted" name="RFCCTABEN"></td>' +
            '<td class="text-muted" name="CATABEN"></td>' +
            '<td class="text-muted" name="RFCCTABEN"></td>' +
            '<td class="text-muted" name="CATABEN"></td>';

          $('#cuerpo').html("");
          $('#cuerpo').append(contenido);
          $("#modal-cargando").modal('hide');
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
            console.log("mensaje: "+ data.mensajito);
          } else {
            $("#modal-exito").modal("show");
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
            console.log("mensaje: "+ data.mensajito);
          }
        },
        error: function() {
          $("#modal-cargando").modal('hide');
          $("#modal-error").modal("show");
          console.log("Error al guardar la prueba posible error en el archivo ValidacionSapcontroler");
          //console.log("no se conecta a la base de datos puede ser los drivers");
        }
      });
    }
  }

  function guardarDatos() {
    console.log("entro a la funcion correctamente 1111");
    var form = new FormData(document.getElementById('cosas2'));
    var contenido = "";
    $.ajax({
      url: 'guardarSAP',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-verifica").modal("hide");
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        contenido += '<td class="text-muted" name="FRCCTAORD"></td>' +
          '<td class="text-muted" name="BANCOORDEXT"></td>' +
          '<td class="text-muted" name="CTAORD"></td>' +
          '<td class="text-muted" name="FORMAP"></td>' +
          '<td class="text-muted" name="MONEDAP"></td>' +
          '<td class="text-muted" name="MONTOP"></td>' +
          '<td class="text-muted" name="NUMEROPERP"></td>' +
          '<td class="text-muted" name="Complemento"></td>' +
          '<td class="text-muted" name="RFCCTABEN"></td>' +
          '<td class="text-muted" name="CATABEN"></td>' +
          '<td class="text-muted" name="RFCCTABEN"></td>' +
          '<td class="text-muted" name="CATABEN"></td>';

        $('#cuerpo').html("");
        $('#cuerpo').append(contenido);
        $("#modal-cargando").modal("hide");
        $("#modal-exito").modal("show");
        incidencias = false;
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
          console.log("mensaje: "+ data.mensajito);
        } else {
          $("#modal-exito").modal("show");
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
          console.log("mensaje: "+ data.mensajito);
        }
      },
      error: function() {
        console.log("Error al guardar la prueba posible error en el archivo ValidacionSapcontroler.php 111")
        $("#modal-cargando").modal("hide");
        $("#modal-error").modal("show");
      }
    });
  }

  function soloNumeros(texto) {
    //console.log(texto);
    var bandera = false;
    if (texto != null) {
      for (var i = 0; i < letras.length; i++) {
        if (texto.indexOf(letras.charAt(i)) != -1) {
          bandera = true;
        }
      }
    }

    return bandera;
  }

  function soloLetras(texto) {
    //console.log(texto);
    var bandera = false;
    if (texto != null) {
      for (var i = 0; i < num.length; i++) {
        if (texto.indexOf(num.charAt(i)) != -1) {
          bandera = true;
        }
      }
    }

    return bandera;
  }

  function monto(texto) {
    //console.log(texto);
    var bandera = false;
    if (texto != null) {
      for (var i = 0; i < cantidad.length; i++) {
        if (texto.indexOf(cantidad.charAt(i)) != -1) {
          bandera = true;
        }
      }
    }

    return bandera;
  }

  function esRFC(texto) {
    var bandera = false;
    if (texto != null) {
      for (var i = 0; i < rfccaracter.length; i++) {
        if (texto.indexOf(rfccaracter.charAt(i)) != -1) {
          bandera = true;
        }
      }
    }

    return bandera;
  }
</script>

<script type="text/javascript">
  //Tesoreria
  document.getElementById("tesoreria").onclick = function() {
    tesoreria()
  };

  function tesoreria() {
    document.getElementById("DropdownTesoreria").classList.toggle("show");
  }
  //Credito y cobranza
  document.getElementById("credito").onclick = function() {
    credito()
  };

  function credito() {
    document.getElementById("DropdownCredito").classList.toggle("show");
  }
  //SAP
  document.getElementById("sap").onclick = function() {
    sap()
  };

  function sap() {
    document.getElementById("DropdownSAP").classList.toggle("show");
  }
  $(document).ready(function() {

    // get the name of uploaded file
    $('input[type="file"]').change(function() {
      var value = $("input[type='file']").val();
      $('.js-value').text(value);
    });
  });
</script>
<!-- Funcion para cambio de atributo sidemenu -->
<script type="text/javascript">
  window.addEventListener('load', icontesoreria, false);

  function icontesoreria() {
    var contenedorTesoreria = document.getElementById('tesoreria');
    contenedorTesoreria.addEventListener('mouseover', cambiarTesoreria, false);
    contenedorTesoreria.addEventListener('mouseout', restaurarTesoreria, false);
  }

  function restaurarTesoreria() {
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency.svg')}}";
  }

  function cambiarTesoreria() {
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconcredito, false);

  function iconcredito() {
    var contenedorCredito = document.getElementById('credito');
    contenedorCredito.addEventListener('mouseover', cambiarCredito, false);
    contenedorCredito.addEventListener('mouseout', restaurarCredito, false);
  }

  function restaurarCredito() {
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay.svg')}}";
  }

  function cambiarCredito() {
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconsap, false);

  function iconsap() {
    var contenedorSAP = document.getElementById('sap');
    contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
    contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
  }

  function restaurarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank.svg')}}";
  }

  function cambiarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconcovestro, false);

  function iconcovestro() {
    var contenedorCovestro = document.getElementById('covestro');
    contenedorCovestro.addEventListener('mouseover', cambiarCovestro, false);
    contenedorCovestro.addEventListener('mouseout', restaurarCovestro, false);
  }

  function restaurarCovestro() {
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning.svg')}}";
  }

  function cambiarCovestro() {
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning-white.svg')}}";
  }
</script>
<script type="text/javascript">
  /*input file*/
  $(document).on('click', '.upload-field', function() {
    var file = $(this).parent().parent().parent().find('.input-file');
    file.trigger('click');
  });

  $(document).on('change', '.input-file', function() {
    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
  });
</script>
<!-- Tablas Pagination -->
<script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
  var datableble = $('#tableSap').dataTable({
    "paging": false,
    "ordering": false,
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });
</script>
<!-- Subir archivo -->
<script type="text/javascript">
  var onDragEnter = function(event) {
      $(".br_dropzone").addClass("dragover");
    },

    onDragOver = function(event) {
      event.preventDefault();
      if (!$(".br_dropzone").hasClass("dragover"))
        $(".br_dropzone").addClass("dragover");
    },

    onDragLeave = function(event) {
      event.preventDefault();
      $(".br_dropzone").removeClass("dragover");
    },

    onDrop = function(event) {
      $(".br_dropzone").removeClass("dragover");
      $(".br_dropzone").addClass("dragdrop");
      //console.log(event.originalEvent.dataTransfer.files);
    };

  $(".br_dropzone")
    .on("dragenter", onDragEnter)
    .on("dragover", onDragOver)
    .on("dragleave", onDragLeave)
    .on("drop", onDrop);
</script>
<!-- Fin scripts -->