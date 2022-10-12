@extends('Plantilla.plantilla')
@if(Session::get('tipo') != 1)
@section('title','Layout Crédito y C.')
@section('usuario','Usuario Crédito y C.')
@section('validacionMenu','no-mostrar')
@section('tesoreriaMenu','no-mostrar')
@section('sapMenu','no-mostrar')
@section('clientesMenu','no-mostrar')
@section('concentradoMenu','no-mostrar')
@section('covestroMenu','no-mostrar')
@section('reportesMenu','no-mostrar')
@if(Session::get('resp') == 0)
@section('responsablesMenu','no-mostrar')
@endif
@else
@section('title','Administrador')
@endif
@section('creditoMenu','credito-active')
@section('contenido')
            <!--Encabezado-->
            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h2 class="tect-left"><i class="icon-cloud-upload"></i>Validacion de archivos</h2>
              </div>
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p class="text-muted lead text-left">Crédito y Cobranza<img href="" src="{{asset('assets/img/pay.svg')}}" class="icon-header"></p>
              </div>
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <hr class="underline">
              </div>
            </div>
            <!-- Fin encabezado -->
          <form id="formulario" action="javascript:cargarTabla();" method="post">
            {{csrf_field()}}
            <!-- Seleciona layout -->
            <div class="row"><!-- Seleccion de layout-->
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="form-group">
                    <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2">
                      <label for="layout" class="text-muted control-label layout-select"><small>Selecciona un Layout:</small></label>
                    </div>
                    <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2">
                      <select id="layout" name="layout" class="form-control layo">
                        @foreach($layout as $l)
                          <option value="{{$l->id_lc}}">{{$l->nombre}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
              </div>
            </div>
            <br>
            <!-- Fin seleciona layout -->

            <!-- Drop area -->
            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">


                  <label for="file" class="br_dropzone">
                    <input type="file" id="file" name="excel[]" onchange="if(this.files.length == 1){this.form.fileName.value = this.files[0].name;}if(this.files.length == 2){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name}if(this.files.length == 3){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name}if(this.files.length == 4){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name}if(this.files.length == 5){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name+'-'+this.files[4].name} if(this.files.length > 5){this.form.fileName.value = 'No puedes subir mas de 5 archivos'}" required multiple>

                    <input type="text" id="fileName" name="fileName" placeholder="Arrastra tu archivo aqu&iacute;" readonly>
                  </label>


              </div>
            </div>
            <!-- fin drop area -->
            <div class="col-12 col-xs-12 col-sm-12 col-md-1 col-lg-1 col-md-offset-5 col-lg-offset-5">
              <button type="submit" name="cargarArchivo" class="button btn-blue"><small>Subir</small></button>
            </div>

          </form>
          <!--Encabezado-->
          <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <p class="text-muted lead text-left">Resultados de validación<img href="" src="{{asset('assets/img/pay.svg')}}" class="icon-header"></p>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr class="underline">
            </div>
          </div>
          <!-- Fin encabezado -->
          <!-- Tabla de resultados -->
            <div class="row" id="tabla">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">
                <br>
                <table class="display AllDataTable table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <th class="text-center text-muted">Número de folio<small></small></th>
                      <th class="text-center text-muted">Clearing Document<small></small></th>
                      <th class="text-center text-muted">Tipo de moneda<small></small></th>
                      <th class="text-center text-muted">Tipo de cambio<small></small></th>
                      <th class="text-center text-muted">Número de parcialidad<small></small></th>
                      <th class="text-center text-muted">Importe del saldo anterior<small></small></th>
                      <th class="text-center text-muted">Importe de saldo pagado<small></small></th>
                      <th class="text-center text-muted">Importe de saldo insoluto<small></small></th>
                    </tr>
                  </thead>
                  <tbody id="cuerpo developers">
                    <tr>
                      <td class="text-center text-muted" name="NUMPAG"><small></small></td>
                      <td class="text-center text-muted" name="NUMPAG"><small></small></td>
                      <td class="text-center text-muted" name="MONEDA"><small></small></td>
                      <td class="text-center text-muted" name="TIPCAMBIO"><small></small></td>
                      <td class="text-center text-muted" name="NUMPARCIALIDAD"><small></small></td>
                      <td class="text-center text-muted" name="IMPSALDOANT"><small></small></td>
                      <td class="text-center text-muted" name="IMPPAGADO"><small></small></td>
                      <td class="text-center text-muted" name="IMPSALDOINS"><small></small></td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
            <br>
            <!-- Fin de tabla de resultados -->
            <!-- Boton Enviar -->
          <div class="col-4 col-sm-4 col-md-2 col-lg-2 col-md-offset-3 col-lg-offset-3">
            <form action="javascript:guardarDatosVerificar();" method="post" id="cosas"><!--Este es tu form solo descomenta-->
              {{csrf_field()}}
              <input type="hidden" name="nombre_excel" id="archivo1" value="">
              <button type="submit" class="button btn-green"><small>Enviar a integración</small></button>
            </form>
          </div>
          <!-- Fin boton enviar -->

          <!-- Boton volver a cargar -->

          <div class="col-4 col-sm-4 col-md-2 col-lg-2 ">
            <form action="javascript:eliminarDatos();" method="post" id="cosas">
            <!--form action="javascript:guardarDatosVerificar();" method="post" id="cosas2"--><!--Este es tu form solo descomenta-->
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
          <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido. Verifica que elegiste el layout correcto</p>
          <p><div id="mens"></div></p>
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
              <input type="hidden" name="nombre_excel" id="archivo2" value="">
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
<!-- Fin modal -->
<!-- Modal -->
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

    <script type="text/javascript" src="{{asset('assets/js/dropmenu.js')}}"></script>
    <script type="text/javascript">
      var may = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
      var min = "abcdefghijklmnñopqrstuvwxyz";
      var num = "1234567890.,^`+-*/_=¨´~{}[]:;$%&()#@";
      var letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ"
      var cantidad = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ,^`+-*/_=¨´~{}[]:;$%&()#@"
      var incidencias = false;

      function cargarTabla(){
        var form = new FormData(document.getElementById('formulario'));
        var contenido = "";
        contenido += '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">'+
                  '<br>'+
                  '<table class="display table table-bordered table-hover table-striped" id="tablaCredito">'+
                    '<thead>'+
                      '<tr>'+
                        '<th class="text-center text-muted">Número de folio<small></small></th>'+
                        '<th class="text-center text-muted">Clearing Document<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de moneda<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de cambio<small></small></th>'+
                        '<th class="text-center text-muted">Número de parcialidad<small></small></th>'+
                        '<th class="text-center text-muted">Importe del saldo anterior<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo pagado<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo insoluto<small></small></th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody id="cuerpo developers">';
        if($("#layout").val() != ""){
          console.log("El valor de archivo es "+$("#file").val())
          if($("#file").val() != null && $("#file").val() != ""){
            $("#archivo1").val($("#file").val());
            $("#archivo2").val($("#file").val());
            $.ajax({
              url: 'cargarCredito',
              type: 'post',
              data: form,
              processData: false,
              contentType: false,
              beforeSend: function(){
                  $("#modal-cargando").modal('show');
                  $("#subir").attr("disabled", true);
                },
              success: function(data){
                if(data.length < 1){
                  console.log("No habia datos en tu archivo de excel.");
                }
                else{
                  if(data.respuesta == 2){
                    dm = data.mensaje;
                    cod_error = dm.split(":");
                    $("#mens").html("");
                    if(cod_error[0] == "Undefined index"){
                      $("#mens").html("No se reconoce la columna "+cod_error[1]+" en las hojas de tu archivo de excel con nombre "+data.archivo+".");
                    }
                    else{
                      $("#mens").html(data.mensaje+". Este error se muestra en el archivo "+data.archivo+".");
                    }
                    $("#modal-cargando").modal('hide');
                    $("#modal-falta").modal('show');
                  }
                  else{
                    for(var i=0; i<data.length; i++){
                      contenido+='<tr>';
                      contenido+='<td class="text-muted">'+data[i].folio+'</td>';
                      contenido+='<td class="text-muted">'+data[i].clearing+'</td>';
                      contenido+='<td class="text-muted">'+data[i].moneda+'</td>';
                      contenido+='<td class="text-muted">'+data[i].tipo_cambio+'</td>';
                      contenido+='<td class="text-muted">'+data[i].parcialidad+'</td>';
                      contenido+='<td class="text-muted">'+data[i].impsaldoant+'</td>';
                      contenido+='<td class="text-muted">'+data[i].imppagado+'</td>';
                      contenido+='<td class="text-muted">'+(data[i].impsaldoant - data[i].imppagado)+'</td>';
                      contenido+='</tr>';

                    }

                    contenido += '</tbody></table>'+
                      '<script type="text/javascript">'+
                        '$("#tablaCredito").DataTable({'+
                        '"ordering": false,'+
                          'language:{'+
                            '"sProcessing":     "Procesando...",'+
                            '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                            '"sZeroRecords":    "No se encontraron resultados",'+
                            '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                            '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                            '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                            '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                            '"sInfoPostFix":    "",'+
                            '"sSearch":         "Buscar:",'+
                            '"sUrl":            "",'+
                            '"sInfoThousands":  ",",'+
                            '"sLoadingRecords": "Cargando...",'+
                            '"oPaginate": {'+
                                '"sFirst":    "Primero",'+
                                '"sLast":     "Último",'+
                                '"sNext":     "Siguiente",'+
                                '"sPrevious": "Anterior"'+
                            '},'+
                            '"oAria": {'+
                                '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                                '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                            '}'+
                          '}'+
                        '});'+
                      '<\/script></div>';

                    document.getElementById("tabla").removeChild(document.getElementById("scroll"))
                    $('#tabla').append(contenido);
                    $("#subir").attr("disabled", false);
                    $("#modal-cargando").modal('hide');
                  }
                }

                
              },
              error: function(){
                console.log("Error al guardar la prueba")
                  $("#modal-cargando").modal('hide');
                  $("#modal-falta").modal('show');
              }
            });
          }
          else{
            $("#modal-no-archivo").modal("show");
          }
        }
        else{
          $("#modal-cargando").modal('hide');
          $("#modal-layout").modal("show");
          $("#subir").attr("disabled", false);
        }
      }

      function eliminarDatos(){
        var form = new FormData(document.getElementById('cosas'));
        var contenido = "";
        contenido += '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">'+
                  '<br>'+
                  '<table class="display table table-bordered table-hover table-striped" id="tablaCredito">'+
                    '<thead>'+
                      '<tr>'+
                        '<th class="text-center text-muted">Número de folio<small></small></th>'+
                        '<th class="text-center text-muted">Clearing Document<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de moneda<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de cambio<small></small></th>'+
                        '<th class="text-center text-muted">Número de parcialidad<small></small></th>'+
                        '<th class="text-center text-muted">Importe del saldo anterior<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo pagado<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo insoluto<small></small></th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody id="cuerpo developers">';
        $.ajax({
          url: 'recargarCredito',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
              $("#modal-cargando").modal('show');
            },
          success: function(data){
            contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                    '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                    '<td class="text-center text-muted" name="CTAORD"></td>'+
                    '<td class="text-center text-muted" name="FORMAP"></td>'+
                    '<td class="text-center text-muted" name="MONEDAP"></td>'+
                    '<td class="text-center text-muted" name="MONTOP"></td>'+
                    '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                    '<td class="text-center text-muted" name="FECHAPAG"></td>';

           contenido += '</tbody></table>'+
                '<script type="text/javascript">'+
                  '$("#tablaCredito").DataTable({'+
                  '"ordering": false,'+
                    'language:{'+
                      '"sProcessing":     "Procesando...",'+
                      '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                      '"sZeroRecords":    "No se encontraron resultados",'+
                      '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                      '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                      '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                      '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                      '"sInfoPostFix":    "",'+
                      '"sSearch":         "Buscar:",'+
                      '"sUrl":            "",'+
                      '"sInfoThousands":  ",",'+
                      '"sLoadingRecords": "Cargando...",'+
                      '"oPaginate": {'+
                          '"sFirst":    "Primero",'+
                          '"sLast":     "Último",'+
                          '"sNext":     "Siguiente",'+
                          '"sPrevious": "Anterior"'+
                      '},'+
                      '"oAria": {'+
                          '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                          '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                      '}'+
                    '}'+
                  '});'+
                '<\/script></div>';

            document.getElementById("tabla").removeChild(document.getElementById("scroll"))
            $('#tabla').append(contenido);
            $('#file').val("");
            incidencias = false;
            $('#fileName').val("Arrastra tu archivo aquí")
            $("#modal-cargando").modal('hide');
          },
          error: function(){
            console.log("Error al guardar la prueba")
          }
        });
      }

      function guardarDatosVerificar(){
        var form = new FormData(document.getElementById('cosas'));
        var contenido = "";
        contenido += '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">'+
                  '<br>'+
                  '<table class="display table table-bordered table-hover table-striped" id="tablaCredito">'+
                    '<thead>'+
                      '<tr>'+
                        '<th class="text-center text-muted">Número de folio<small></small></th>'+
                        '<th class="text-center text-muted">Clearing Document<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de moneda<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de cambio<small></small></th>'+
                        '<th class="text-center text-muted">Número de parcialidad<small></small></th>'+
                        '<th class="text-center text-muted">Importe del saldo anterior<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo pagado<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo insoluto<small></small></th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody id="cuerpo developers">';
        if(incidencias == true){
          $("#modal-verifica").modal("show");
          incidencias = false;
        }
        else{
          $.ajax({
            url: 'guardarCredito',
            type: 'post',
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function(){
              $("#modal-cargando").modal('show');
            },
            success: function(data){
              contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                      '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                      '<td class="text-center text-muted" name="CTAORD"></td>'+
                      '<td class="text-center text-muted" name="FORMAP"></td>'+
                      '<td class="text-center text-muted" name="MONEDAP"></td>'+
                      '<td class="text-center text-muted" name="MONTOP"></td>'+
                      '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                      '<td class="text-center text-muted" name="FECHAPAG"></td>'+
                      '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                      '<td class="text-center text-muted" name="CATABEN"></td>'+
                      '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                      '<td class="text-center text-muted" name="CATABEN"></td>';

              contenido += '</tbody></table>'+
                '<script type="text/javascript">'+
                  '$("#tablaCredito").DataTable({'+
                  '"ordering": false,'+
                    'language:{'+
                      '"sProcessing":     "Procesando...",'+
                      '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                      '"sZeroRecords":    "No se encontraron resultados",'+
                      '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                      '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                      '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                      '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                      '"sInfoPostFix":    "",'+
                      '"sSearch":         "Buscar:",'+
                      '"sUrl":            "",'+
                      '"sInfoThousands":  ",",'+
                      '"sLoadingRecords": "Cargando...",'+
                      '"oPaginate": {'+
                          '"sFirst":    "Primero",'+
                          '"sLast":     "Último",'+
                          '"sNext":     "Siguiente",'+
                          '"sPrevious": "Anterior"'+
                      '},'+
                      '"oAria": {'+
                          '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                          '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                      '}'+
                    '}'+
                  '});'+
                '<\/script></div>';

            document.getElementById("tabla").removeChild(document.getElementById("scroll"))
            $('#tabla').append(contenido);
              $("#modal-cargando").modal('hide');
              if(data.respuesta == "2" || data.respuesta == 2){
                $("#modal-no-carga").modal("show");
              }
              else{
                $("#modal-exito").modal("show");
              }
            },
            error: function(){
              $("#modal-verifica").modal("hide");
              $("#modal-error").modal("show");
              console.log("Error al guardar la prueba")
            }
          });
        }
      }

      function guardarDatos(){
        var form = new FormData(document.getElementById('cosas3'));
        var contenido = "";
        contenido += '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">'+
                  '<br>'+
                  '<table class="display table table-bordered table-hover table-striped" id="tablaCredito">'+
                    '<thead>'+
                      '<tr>'+
                        '<th class="text-center text-muted">Número de folio<small></small></th>'+
                        '<th class="text-center text-muted">Clearing Document<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de moneda<small></small></th>'+
                        '<th class="text-center text-muted">Tipo de cambio<small></small></th>'+
                        '<th class="text-center text-muted">Número de parcialidad<small></small></th>'+
                        '<th class="text-center text-muted">Importe del saldo anterior<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo pagado<small></small></th>'+
                        '<th class="text-center text-muted">Importe de saldo insoluto<small></small></th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody id="cuerpo developers">';
        $.ajax({
          url: 'guardarCredito',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
              $("#modal-cargando").modal('show');
              $("#subir").attr("disabled", true);
            },
          success: function(data){
            contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                    '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                    '<td class="text-center text-muted" name="CTAORD"></td>'+
                    '<td class="text-center text-muted" name="FORMAP"></td>'+
                    '<td class="text-center text-muted" name="MONEDAP"></td>'+
                    '<td class="text-center text-muted" name="MONTOP"></td>'+
                    '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                    '<td class="text-center text-muted" name="FECHAPAG"></td>'+
                    '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-center text-muted" name="CATABEN"></td>'+
                    '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-center text-muted" name="CATABEN"></td>';

            contenido += '</tbody></table>'+
                '<script type="text/javascript">'+
                  '$("#tablaCredito").DataTable({'+
                  '"ordering": false,'+
                    'language:{'+
                      '"sProcessing":     "Procesando...",'+
                      '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                      '"sZeroRecords":    "No se encontraron resultados",'+
                      '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                      '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                      '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                      '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                      '"sInfoPostFix":    "",'+
                      '"sSearch":         "Buscar:",'+
                      '"sUrl":            "",'+
                      '"sInfoThousands":  ",",'+
                      '"sLoadingRecords": "Cargando...",'+
                      '"oPaginate": {'+
                          '"sFirst":    "Primero",'+
                          '"sLast":     "Último",'+
                          '"sNext":     "Siguiente",'+
                          '"sPrevious": "Anterior"'+
                      '},'+
                      '"oAria": {'+
                          '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                          '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                      '}'+
                    '}'+
                  '});'+
                '<\/script></div>';

            document.getElementById("tabla").removeChild(document.getElementById("scroll"))
            $('#tabla').append(contenido);
            $("#modal-verifica").modal("hide");
            $("#modal-exito").modal("show");
            incidencias = false;
          },
          error: function(){
            console.log("Error al guardar la prueba")
            $("#modal-verifica").modal("hide");
            $("#modal-error").modal("show");
          }
        });
      }

      function soloNumeros(texto){
        console.log(texto);
        var bandera = false;
        if(texto != null){
          for(var i = 0; i<letras.length; i++){
            if(texto.indexOf(letras.charAt(i)) != -1){
              bandera = true;
            }
          }
        }

        return bandera;
      }

      function soloLetras(texto){
        console.log(texto);
        var bandera = false;
        if(texto != null){
          for(var i = 0; i<num.length; i++){
            if(texto.indexOf(num.charAt(i)) != -1){
              bandera = true;
            }
          }
        }

        return bandera;
      }

      function monto(texto){
        console.log(texto);
        var bandera = false;
        if(texto != null){
          for(var i = 0; i<cantidad.length; i++){
            if(texto.indexOf(cantidad.charAt(i)) != -1){
              bandera = true;
            }
          }
        }

        return bandera;
      }
    </script>
  <script type="text/javascript">
      //Tesoreria
      document.getElementById("tesoreria").onclick = function() {tesoreria()};
      function tesoreria() {
        document.getElementById("DropdownTesoreria").classList.toggle("show");
      }
      //Credito y cobranza
      document.getElementById("credito").onclick = function() {credito()};
      function credito(){
        document.getElementById("DropdownCredito").classList.toggle("show");
      }
      //SAP
      document.getElementById("sap").onclick = function() {sap()};
      function sap(){
        document.getElementById("DropdownSAP").classList.toggle("show");
      }
      $(document).ready(function() {

    // get the name of uploaded file
   $('input[type="file"]').change(function(){
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

    function restaurarTesoreria(){
      var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency.svg')}}";
    }

    function cambiarTesoreria() {
      var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency-white.svg')}}";
    }

  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconcredito, false);

    function iconcredito(){
      var contenedorCredito = document.getElementById('credito');
      contenedorCredito.addEventListener('mouseover', cambiarCredito, false);
      contenedorCredito.addEventListener('mouseout', restaurarCredito, false);
    }

    function restaurarCredito(){
      var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay.svg')}}";
    }

    function cambiarCredito() {
      var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay-white.svg')}}";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconsap, false);

    function iconsap(){
      var contenedorSAP = document.getElementById('sap');
      contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
      contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
    }

    function restaurarSAP(){
      var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank.svg')}}";
    }

    function cambiarSAP() {
      var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconcovestro, false);

    function iconcovestro(){
      var contenedorCovestro = document.getElementById('covestro');
      contenedorCovestro.addEventListener('mouseover', cambiarCovestro, false);
      contenedorCovestro.addEventListener('mouseout', restaurarCovestro, false);
    }

    function restaurarCovestro(){
      var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning.svg')}}";
    }

    function cambiarCovestro() {
      var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning-white.svg')}}";
    }
  </script>
  <script type="text/javascript">
      /*input file*/
      $(document).on('click', '.upload-field', function(){
        var file = $(this).parent().parent().parent().find('.input-file');
      file.trigger('click');
      });

      $(document).on('change', '.input-file', function(){
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      });
  </script>
  <!-- Tablas Pagination -->
  <script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>


  <!-- Subir archivo -->
  <script type="text/javascript">

      var onDragEnter = function (event) {
        $(".br_dropzone").addClass("dragover");
      },

      onDragOver = function (event) {
        event.preventDefault();
        if (!$(".br_dropzone").hasClass("dragover"))
            $(".br_dropzone").addClass("dragover");
      },

      onDragLeave = function (event) {
        event.preventDefault();
        $(".br_dropzone").removeClass("dragover");
      },

      onDrop = function (event) {
        $(".br_dropzone").removeClass("dragover");
        $(".br_dropzone").addClass("dragdrop");
        console.log(event.originalEvent.dataTransfer.files);
      };

      $(".br_dropzone")
      .on("dragenter", onDragEnter)
      .on("dragover", onDragOver)
      .on("dragleave", onDragLeave)
      .on("drop", onDrop);
  </script>
  <script type="text/javascript">
$(document).ready( function () {
$('.AllDataTable').DataTable({
"ordering": false,
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
});
} );
</script>
