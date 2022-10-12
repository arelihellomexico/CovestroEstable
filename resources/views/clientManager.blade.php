@extends('Plantilla.plantilla')
@section('title','Gestor de clientes')
@section('clientesMenu','clientes-active')
@section('contenido')
<!-- Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left"><i class="icon-people"></i> Gestor de clientes</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Cargar archivo <i class="icon-people icon-header"></i></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<!-- Drag & Drop Area -->
<div class="col-12 col-sm-12 col-md-12 col-lg-12">
  <form action="javascript:guardarClientes();" method="post" id="formulario">
    {{csrf_field()}}
    <div class="row">
      <!-- Drop area -->
      <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <label for="file" class="br_dropzone layo2">
            <input type="file" id="file" name="excel" onchange="this.form.fileName.value = this.files[0].name" required>
            <input type="text" id="fileName" name="fileName" placeholder="Arrastra tu archivo aqu&iacute;" readonly>
          </label>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-1 col-lg-1 col-md-offset-5 col-lg-offset-5">
          <button type="submit" name="cargarArchivo" class="button btn-blue"><small>Subir</small></button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- Fin Drag & Drop Area -->

<!-- Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Prueba de carga de archivos <i class="icon-people icon-header"></i></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<!-- Tabla de resultados -->
  <form class="" action="" method="post">
  <div class="row" id="cont">
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">
        <br>
        <table class="display AllDataTable table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th class="text-center text-muted"><small>ID_CLIENTE</small></th>
              <th class="text-center text-muted"><small>RFC_C</small></th>
              <th class="text-center text-muted"><small>NOMBRE_C</small></th>
              <th class="text-center text-muted"><small>CPOSTAL_C</small></th>
              <th class="text-center text-muted"><small>PAIS_C</small></th>
              <th class="text-center text-muted"><small>NOMBRE2_C</small></th>
              <th class="text-center text-muted"><small>DIRECCION</small></th>
              <th class="text-center text-muted"><small>TELEFONO</small></th>
              <th class="text-center text-muted"><small>LOCALIDAD_C</small></th>
              <th class="text-center text-muted"><small>MUNICIPIO_C</small></th>
              <th class="text-center text-muted"><small>ESTADO_C</small></th>
              <th class="text-center text-muted"><small>RESIDENCIAFISCAL</small></th>
              <th class="text-center text-muted"><small>NUMREGIDTRIB</small></th>
            </tr>
          </thead>
          <tbody id="cuerpo">
            @foreach($clientes as $c)
            <tr>
              <td class="text-center text-muted" name="id_cliente" value="">{{ $c->id_cliente }}</td>
              <td class="text-center text-muted" name="rfc_c" value="">{{ $c->rfc_c }}</td>
              <td class="text-center text-muted" name="nombre_c" value="">{{ $c->nombre_c }}</td>
              <td class="text-center text-muted" name="cpostal_c" value="">{{ $c->cpostal_c }}</td>
              <td class="text-center text-muted" name="pais_c" value="">{{ $c->pais_c }}</td>
              <td class="text-center text-muted" name="nombre2_c" value="">{{ $c->nombre2_c }}</td>
              <td class="text-center text-muted" name="direccion" value="">{{ $c->direccion_c }}</td>
              <td class="text-center text-muted" name="telefono" value="">{{ $c->telefono_c }}</td>
              <td class="text-center text-muted" name="localidad_c" value="">{{ $c->localidad_c }}</td>
              <td class="text-center text-muted" name="municipio_c" value="">{{ $c->municipio_c }}</td>
              <td class="text-center text-muted" name="estado_c" value="">{{ $c->estado_c }}</td>
              <td class="text-center text-muted" name="residenciafiscal" value="">{{ $c->residenciafiscal }}</td>
              <td class="text-center text-muted" name="numregidtrib" value="">{{ $c->numregidtrib }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
  <br>
  <!--div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-2 col-md-offset-1 col-lg-offset-1">
      <button type="reset" name="button" class="button btn-pink"><small>Cancelar</small></button>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-2">
      <button type="submit" name="button" class="button btn-green"><small>Guardar Layout</small></button>
    </div>
  </div-->
  </form>
  <br>
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
          <div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>
            <img src="{{asset('assets/img/signo2.png')}}">
            <h3><strong>Archivo Incompleto</strong></h3>
            <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido.</p>
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
  <!-- Fin de tabla de resultados -->

@endsection
<script type="text/javascript" src="{{asset('assets/js/dropmenu.js')}}"></script>
<script type="text/javascript">
      function guardarClientes(){
        var form = new FormData(document.getElementById('formulario'));
        var contenido = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll">'+
        '<br>'+
        '<table class="display table table-bordered table-hover table-striped" id="tableClientes">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-center text-muted"><small>ID_CLIENTE</small></th>'+
              '<th class="text-center text-muted"><small>RFC_C</small></th>'+
              '<th class="text-center text-muted"><small>NOMBRE_C</small></th>'+
              '<th class="text-center text-muted"><small>CPOSTAL_C</small></th>'+
              '<th class="text-center text-muted"><small>PAIS_C</small></th>'+
              '<th class="text-center text-muted"><small>NOMBRE2_C</small></th>'+
              '<th class="text-center text-muted"><small>DIRECCION</small></th>'+
              '<th class="text-center text-muted"><small>TELEFONO</small></th>'+
              '<th class="text-center text-muted"><small>LOCALIDAD_C</small></th>'+
              '<th class="text-center text-muted"><small>MUNICIPIO_C</small></th>'+
              '<th class="text-center text-muted"><small>ESTADO_C</small></th>'+
              '<th class="text-center text-muted"><small>RESIDENCIAFISCAL</small></th>'+
              '<th class="text-center text-muted"><small>NUMREGIDTRIB</small></th>'+
            '</tr>'+
          '</thead>';
        if($("#file").val() == null || $("#file").val() == ""){
          $("#modal-no-archivo").modal("show");
        }
        else{
          $.ajax({
          url: 'guardarClientes',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
            $("#modal-cargando").modal('show');
            $("#subir").attr("disabled", true);
            $('#cuerpo').html("");
          },
          success: function(data){
            if(data.length < 1){
              console.log("No habia datos en tu archivo de excel.");
            }
            else{
              for(var i=0; i<data.length; i++){
                contenido+='<tr>';
                contenido+='<td class="text-muted">'+data[i].id_cliente+'</td>';
                contenido+='<td class="text-muted">'+data[i].rfc_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].nombre_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].cpostal_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].pais_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].nombre2_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].direccion_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].telefono_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].localidad_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].municipio_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].estado_c+'</td>';
                contenido+='<td class="text-muted">'+data[i].residenciafiscal+'</td>';
                contenido+='<td class="text-muted">'+data[i].numregidtrib+'</td>';
              }

              contenido+='<script type="text/javascript">'+
                '$("#tableClientes").DataTable({'+
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
              $("#modal-cargando").modal('hide');
              $("#modal-exito").modal('show');
              document.getElementById('cont').removeChild(document.getElementById('scroll'));
              $('#cont').append(contenido);
            }
          },
          error: function(){
            console.log("Error al guardar la prueba")
            $("#modal-cargando").modal('hide');
            $("#modal-falta").modal('show');
          }
        });
        }
      }
    </script>
