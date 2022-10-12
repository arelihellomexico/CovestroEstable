@extends('Plantilla.plantilla')

@section('header')
@section('title','Administrador de correos')
@section('barra-superior')

@section('sidemenu')
@section('clientesMenu','clientes-active')
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="icon-people"></i> ADMINISTRADOR DE CORREOS</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Clientes</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form id="formProcesos" action="javascript:buscarProcesos();" method="post">
      {{ csrf_field() }}
      <div class="col-md-1 col-lg-1">
        
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-3">
        <div class="form-group input-field">
          <label for="default-input-date">Buscar clientes con id:</label>
          <input id="id_cliente" name="id_cliente" type="text" placeholder="Escriba id del cliente" class="input-date">
          <p class="label-error"></p>
        </div>
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-4">
        <div class="form-group input-field">
          <label for="default-input-date">Buscar clientes con RFC o Nombre:</label>
          <input id="cliente" name="cliente" type="text" placeholder="Escriba RFC o Nombre del cliente" class="input-date">
        <p class="label-error"></p>
        </div>
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-3">
        <div class="input-field">
          <button type="submit" class="button btn-blue buscar-tabla" name="button">Buscar</button>
        </div>
      </div>
      <div class="col-md-2 col-lg-1">
        
      </div>
    </form>
    <div id="bodyProcesos">
      
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Correos registrados</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bodyArchivos">
    <div id="contArchivos"></div>
  </div>
</div>
                <div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Tu layout se ha guardado exitosamente.
                          </p>
                        </center>
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
                <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al mostrar los datos.
                          </p>
                        </center>
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
                <div class="modal fade" id="modal-exito-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Se agregó el correo exitosamente.
                          </p>
                        </center>
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
                <div class="modal fade" id="modal-error-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al agregar tu correo.
                          </p>
                        </center>
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
                <div class="modal fade" id="modal-exito-finaliza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Se ha eliminado el correo satisfactoriamente.
                          </p>
                        </center>
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
                <div class="modal fade" id="modal-error-finaliza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al eliminar el correo.
                          </p>
                        </center>
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
<!-- Fin encabezado -->
<script type="text/javascript">
  $(document).ready(function () {
    var tablaProcesos = '<div id="contProcesos">';
    tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">RFC</th>'+
          '<th class="text-muted text-center">Nombre</th>'+
          '<th class="text-muted text-center">Ver</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($clientes as $p)
      tablaProcesos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->id_cliente}}</td>'+
                '<td id="date">{{$p->rfc_c}}</td>'+
                '<td>{{$p->nombre_c}}</td>'+
                '<td><a href="#" onclick="verProceso({{ $p->id_cliente }}, \'{{ $p->nombre_c }}\')"><i class="fa fa-eye"></i></a></td>'+
              '</tr>';

      @endforeach
      tablaProcesos += '</tbody>'+
    '</table>';
    tablaProcesos += '<script type="text/javascript">$("#tablaProcesos").DataTable({'+
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
        '});<\/script></div>';

      $("#bodyProcesos").html(tablaProcesos);

      
  })
</script>
<script type="text/javascript">
  
  function verProceso(id, nombre) {
    id_proceso = id;
    $.ajax({
      url: 'seleccionarCliente',
      type: 'get',
      data: {id_cliente: id},
      success: function (data) {
        $("#proceso").val(id);
        document.getElementById("bodyArchivos").removeChild(document.getElementById("contArchivos"));
        var tablaArchivos = '<div id="contArchivos"><form id="formNuevo" action="javascript:actualizarStatus();" method="post">{{ csrf_field() }}<input type="hidden" name="idCliente" id="idCliente" value="'+id+'">';

      //tablaArchivos += '</form>';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
          '<thead>'+
            '<tr>'+
              '<th colspan="2" class="text-muted text-center">'+nombre+'</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center">Correo</th>'+
              '<th class="text-muted text-center">Acción</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center"><input type="email" name="nuevoCorreo" id="nuevoCorreo" class="input-date" placeholder="Agrega un nuevo correo para este cliente"></th>'+
              '<th class="text-muted text-center"><button type="submit" class="button btn-blue buscar-tabla" name="button">Agregar correo</button></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].correo+'</td>';
              tablaArchivos += '<td><a href="#" class="button btn-link" onclick="borrarCorreo('+data[i].id+', '+data[i].id_cliente+', \''+nombre+'\')">Eliminar</a>';
            tablaArchivos += '</td></tr>';
          }
          tablaArchivos += '</tbody>'+
        '</table></form>';
        tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
            '});<\/script></div>';
          $("#bodyArchivos").html(tablaArchivos);
      },
      error: function () {
        $("#modal-exito").modal("show");
      }
    })
  }

  function buscarProcesos(argument) {
    var formulario = new FormData(document.getElementById("formProcesos"));
    $.ajax({
      url: "buscarClientes",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        document.getElementById('bodyProcesos').removeChild(document.getElementById('contProcesos'))
        var tablaProcesos = '<div id="contProcesos">';
        tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">ID del cliente</th>'+
              '<th class="text-muted text-center">RFC</th>'+
              '<th class="text-muted text-center">Nombre</th>'+
              '<th class="text-muted text-center">Ver</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for(var i = 0; i < data.length; i++){
          tablaProcesos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].id_cliente+'</td>'+
                    '<td id="date">'+data[i].rfc_c+'</td>'+
                    '<td>'+data[i].nombre_c+'</td>'+
                    '<td><a href="#" onclick="verProceso('+data[i].id_cliente+', \''+data[0].nombre_c+'\')"><i class="fa fa-eye"></i></a></td>'+
                  '</tr>';

          }
          tablaProcesos += '</tbody>'+
        '</table>';
        tablaProcesos += '<script type="text/javascript">$("#tablaProcesos").DataTable({'+
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
            '});<\/script></div>';

          $("#bodyProcesos").append(tablaProcesos);
      },
      error: function() {
        $("#modal-exito").modal("show");
      }
    })
  }

  

  function actualizarStatus() {
    console.log(nuevoCorreo);
    console.log(document.getElementById("formNuevo"));
    var formulario = new FormData(document.getElementById("formNuevo"));
    console.log(formulario);
    $.ajax({
      url: "agregarCorreos",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        console.log(data)
        //$("#proceso").val(id);
        document.getElementById("bodyArchivos").removeChild(document.getElementById("contArchivos"));
        var tablaArchivos = '<div id="contArchivos"><form id="formNuevo" action="javascript:actualizarStatus();" method="post">{{ csrf_field() }}<input type="hidden" name="idCliente" id="idCliente" value="'+data[0].id_cliente+'">';
        

      //tablaArchivos += '</form>';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
          '<thead>'+
            '<tr>'+
              '<th colspan="2" class="text-muted text-center">'+data[0].nombre_c+'</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center">Correo</th>'+
              '<th class="text-muted text-center">Acción</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center"><input type="email" name="nuevoCorreo" id="nuevoCorreo" class="input-date" placeholder="Agrega un nuevo correo para este cliente"></th>'+
              '<th class="text-muted text-center"><button type="button" class="button btn-blue buscar-tabla" name="submit">Agregar correo</button></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].correo+'</td>';
              tablaArchivos += '<td><a href="#" class="button btn-link" onclick="borrarCorreo('+data[i].id+', '+data[i].id_cliente+', \''+data[0].nombre_c+'\')">Eliminar</a>';
            tablaArchivos += '</td></tr>';
          }
          tablaArchivos += '</tbody>'+
        '</table></form>';
        tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
            '});<\/script></div>';
          $("#bodyArchivos").html(tablaArchivos);
        $("#modal-exito-actualiza").modal("show");
      },
      error: function() {
        $("#modal-error-actualiza").modal("show");
      }
    })
  }

  function borrarCorreo(id_correo, id_cli, nombre) {
    // var formulario = new FormData(document.getElementById("finProceso"));
    $.ajax({
      url: "eliminarCorreos",
      type: "get",
      data: {id_cor: id_correo, id_cliente: id_cli},
      success: function(data){
        if(data.respuesta == 2){
          $("#modal-error-finaliza").modal("show");
        }
        else{
          //$("#proceso").val(id);
        document.getElementById("bodyArchivos").removeChild(document.getElementById("contArchivos"));
        var tablaArchivos = '<div id="contArchivos"><form id="formNuevo" action="javascript:actualizarStatus();" method="post">{{ csrf_field() }}<input type="hidden" name="idCliente" id="idCliente" value="'+id_cli+'">';
        

      //tablaArchivos += '</form>';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
          '<thead>'+
            '<tr>'+
              '<th colspan="2" class="text-muted text-center">'+data[0].nombre_c+'</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center">Correo</th>'+
              '<th class="text-muted text-center">Acción</th>'+
            '</tr>'+
            '<tr>'+
              '<th class="text-muted text-center"><input type="email" name="nuevoCorreo" id="nuevoCorreo" class="input-date" placeholder="Agrega un nuevo correo para este cliente"></th>'+
              '<th class="text-muted text-center"><button type="submit" class="button btn-blue buscar-tabla" name="button" >Agregar correo</button></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].correo+'</td>';
              tablaArchivos += '<td><a href="#" class="button btn-link" onclick="borrarCorreo('+data[i].id+', '+data[i].id_cliente+', \''+nombre+'\')">Eliminar</a>';
            tablaArchivos += '</td></tr>';
          }
          tablaArchivos += '</tbody>'+
        '</table></form>';
        tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
            '});<\/script></div>';
          $("#bodyArchivos").html(tablaArchivos);
          $("#modal-exito-finaliza").modal("show");
        }
      },
      error: function() {
        $("#modal-error-finaliza").modal("show");
      }
    })
  }

  function pasarTimbre(status, archivo) {
    $("#id_ar"+archivo).val(status);
  }
</script>
@endsection
@section('footer')
@endsection
