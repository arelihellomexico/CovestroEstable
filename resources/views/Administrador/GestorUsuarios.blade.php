@extends('Plantilla.plantilla')
@section('title','Gestor de usuarios')
@section('validacionMenu','validacion-active')

@section('contenido')
<!-- Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left"><img src="{{asset('assets/img/team-b.svg')}}" alt="" class="header">Gestor de usuarios</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Agregar super administrador <img src="{{asset('assets/img/team.svg')}}" alt="" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<!-- Seccion add users -->
<form class="" action="javascript:agregarUsuario()" method="post" id="add_user">
  {{csrf_field()}}
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <input type="hidden" name="op" id="op" value="0">
      <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre">
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="usuario">CWID</label>
        <input type="text" class="form-control" id="usuario" placeholder="CWID de 5 caracteres" name="usuario">
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="rol">Tipo de usuario o rol</label>
        <select class="form-control" id="rol" name="rol">
          <option value="1">Administrador</option>
          <option value="4">Usuario SAP</option>
          <option value="2">Usuario Tesorería</option>
          <option value="3">Usuario Crédito y Cobranza</option>
          <option value="5">Usuario Consulta</option>
        </select>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="text" class="form-control" id="email" placeholder="Correo electrónico" name="correo">
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group">
        <input type="checkbox" id="responsable" name="responsable"> Este usuario es responsable de clientes
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs12 col-sm-12 col-md-4 col-lg-2">
      <button type="reset" name="button" class="button btn-pink"><small>Cancelar</small></button>
    </div>
    <div class="col-xs12 col-sm-12 col-md-4 col-lg-2">
      <button type="submit" name="button" class="button btn-green"><small>Guardar</small></button>
    </div>
  </div>
</form>
<br>
  <!-- Encabezado -->
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-muted lead text-left">Listado de usuarios <img src="{{asset('assets/img/team.svg')}}" alt="" class="icon-header"></p>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <hr class="underline">
    </div>
  </div>
  <!-- Fin encabezado -->

  <!-- Tabla de resultados -->
  <div class="row" id="bodyUsuarios">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">
      <table class="display AllDataTable table table-bordered table-hover space table-striped" id="tableSap">
        <thead>
          <tr>
            <th class="text-muted text-center txt-thead"><small>Nombre</small></th>
            <th class="text-muted text-center txt-thead"><small>CWID</small></th>
            <th class="text-muted text-center txt-thead"><small>Tipo de usuario o rol</small></th>
            <th class="text-muted text-center txt-thead"><small>Correo electrónico</small></th>
            <th class="text-muted text-center txt-thead"><small>Editar</small></th>
            <th class="text-muted text-center txt-thead"><small>Eliminar</small></th>
          </tr>
        </thead>
        <tbody id="usuarios">
          @foreach($usuarios as $u)
          <tr>
            <th class="text-center" name="nombre_usuario"><small>{{$u->nombre}}</small></th>
            <th class="text-center" name="nombre_usuario"><small>{{$u->cwid}}</small></th>
            @if($u->tipo == 1)
            <th class="text-center" name="tipo_usuario"><small>Administrador</small></th>
            @else
              @if($u->tipo == 2)
              <th class="text-center" name="tipo_usuario"><small>Usuario Tesorería</small></th>
              @else
                @if($u->tipo == 3)
                <th class="text-center" name="tipo_usuario"><small>Usuario Crédito y Cobranza</small></th>
                @else
                  @if($u->tipo == 4)
                  <th class="text-center" name="tipo_usuario"><small>Usuario SAP</small></th>
                  @else
                  <th class="text-center" name="tipo_usuario"><small>Usuario Consulta</small></th>
                  @endif
                @endif
              @endif
            @endif
            <th class="text-center" name="correo"><small>{{$u->correo}}</small></th>
            <th class="text-center" name="editar"><button class="button btn-transparent" type="button" onclick="editarUsuario('{{$u->cwid}}')"><i class="far fa-edit"></i></button></th>
            <th class="text-center" name="eliminar"><button class="button btn-transparent" type="button" onclick="preliminar('{{$u->cwid}}')"><i class="far fa-trash-alt"></i></button></th>
          </tr>
          @endforeach
        </tbody>
      </table>
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
                Tu usuario se ha guardado exitosamente.
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
                Hubo un problema al guardar tu usuario.
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
    <div class="modal fade" id="modal-verifica-eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <center>
              <img src="{{asset('assets/img/signo.png')}}">
              <h3><strong>Confirmación de Eliminación</strong></h3>
              <p>¿Estás seguro que deseas eliminar este usuario?</p>
            </center>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-xs-5" align="right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              </div>
              <div class="col-xs-7" align="left">
                  <input type="hidden" name="nombre_excel" id="archivo2">
                  <button type="button" class="btn btn-primary" onclick="eliminarUsuario();">Eliminar.</button>
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
          <p>Espera un momento.</p>
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
<!-- Fin seccion add users -->
@endsection
<script type="text/javascript">
var arrastrar = 0;
function agregarUsuario(){
  var tablaProcesos = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">';
    tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
    '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Nombre</th>'+
          '<th class="text-muted text-center">CWID</th>'+
          '<th class="text-muted text-center">Tipo de Usuario o Rol</th>'+
          '<th class="text-muted text-center">Correo</th>'+
          '<th class="text-muted text-center txt-thead"><small>Editar</small></th>'+
            '<th class="text-muted text-center txt-thead"><small>Eliminar</small></th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
  var formulario = new FormData(document.getElementById('add_user'));

  var opcion = '';
  if($('#op').val()==0){
    opcion = 'agregarUsuario';
  }else{
    opcion = 'actualizarUsuario';
    $('#op').val(0);
  }
  $.ajax({
    url:opcion,
    type:'post',
    data:formulario,
    processData: false,
    contentType: false,
    beforeSend: function(){
      $("#modal-cargando").modal('show');
    },
    success: function(data){
      if(data.respuesta=='no'){
        $('#modal-error').modal('show');
      }else{
        for(var i = 0; i < data.length; i++){
          tablaProcesos += '<tr class="text-center" name="nombre_usuario" id="">'+
                    '<td class="text-center" name="nombre_usuario" id="archivos-recientes-tesoreria">'+data[i].nombre+'</td>'+
                    '<td class="text-center" name="nombre_usuario" id="archivos-recientes-tesoreria">'+data[i].cwid+'</td>';
                    if(data[i].tipo == 1){
                       tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Administrador</td>';
                    }
                    else{
                      if(data[i].tipo == 2){
                        tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Tesorería</td>';
                      }
                      else{
                        if(data[i].tipo == 3){
                          tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Crédito y Cobranza</td>';
                        }
                        else{
                          if(data[i].tipo == 4){
                            tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario SAP</td>';
                          }
                          else{
                            tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Consulta</td>';
                          }
                        }
                      }
                    }
                    tablaProcesos += '<td class="text-center" name="correo">'+data[i].correo+'</td>'+
                    '<th class="text-center" name="editar"><button class="button btn-transparent" type="button" onclick="editarUsuario(\''+data[i].cwid+'\')"><i class="far fa-edit"></i></button></th>'+
                    '<th class="text-center" name="eliminar"><button class="button btn-transparent" type="button" onclick="preliminar(\''+data[i].cwid+'\')"><i class="far fa-trash-alt"></i></button></th>'+
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

          document.getElementById('bodyUsuarios').removeChild(document.getElementById('scroll'))
          $("#bodyUsuarios").append(tablaProcesos);
          $("#modal-cargando").modal('hide');
        $('#modal-exito').modal('show');
      }
      $("#usuario").removeAttr('readonly');
    },
    error:function(){
      $("#modal-cargando").modal('hide');
      $('#modal-error').modal('show');
    }
  });
}

function eliminarUsuario(){
  var tablaProcesos = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">';
    tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
    '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Nombre</th>'+
          '<th class="text-muted text-center">CWID</th>'+
          '<th class="text-muted text-center">Tipo de Usuario o Rol</th>'+
          '<th class="text-muted text-center">Correo</th>'+
          '<th class="text-muted text-center txt-thead"><small>Editar</small></th>'+
            '<th class="text-muted text-center txt-thead"><small>Eliminar</small></th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
  $.ajax({
    url:'eliminarUsuario',
    type:'get',
    data:{correo:arrastrar},
    beforeSend: function(){
      $("#modal-verifica-eliminar").modal("hide");
      $("#modal-cargando").modal('show');
    },
    success: function(data){
      if(data.respuesta=='no'){
        $('#modal-error').modal('show');
      }else{
        for(var i = 0; i < data.length; i++){
          tablaProcesos += '<tr class="text-center" name="nombre_usuario" id="">'+
                    '<td class="text-center" name="nombre_usuario" id="archivos-recientes-tesoreria">'+data[i].nombre+'</td>'+
                    '<td class="text-center" name="nombre_usuario" id="archivos-recientes-tesoreria">'+data[i].cwid+'</td>';
                    if(data[i].tipo == 1){
                       tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Administrador</td>';
                    }
                    else{
                      if(data[i].tipo == 2){
                        tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Tesorería</td>';
                      }
                      else{
                        if(data[i].tipo == 3){
                          tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Crédito y Cobranza</td>';
                        }
                        else{
                          if(data[i].tipo == 4){
                            tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario SAP</td>';
                          }
                          else{
                            tablaProcesos += '<td class="text-center" name="tipo_usuario" id="date">Usuario Consulta</td>';
                          }
                        }
                      }
                    }
                    tablaProcesos += '<td class="text-center" name="correo">'+data[i].correo+'</td>'+
                    '<th class="text-center" name="editar"><button class="button btn-transparent" type="button" onclick="editarUsuario(\''+data[i].cwid+'\')"><i class="far fa-edit"></i></button></th>'+
                    '<th class="text-center" name="eliminar"><button class="button btn-transparent" type="button" onclick="preliminar(\''+data[i].cwid+'\')"><i class="far fa-trash-alt"></i></button></th>'+
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

          document.getElementById('bodyUsuarios').removeChild(document.getElementById('scroll'))
          $("#bodyUsuarios").append(tablaProcesos);
          $("#modal-cargando").modal('hide');
        $('#modal-exito').modal('show');
      }
    },
    error:function(){
      $('#modal-error').modal('show');
    }
  });
}

function preliminar(correo){
  arrastrar = correo;
  $("#modal-verifica-eliminar").modal("show");
}

function editarUsuario(correo){

  $.ajax({
    url:'editarUsuario',
    type:'get',
    data:{correo:correo},
    success: function(data){
      if(data.respuesta=='no'){
        $('#modal-error').modal('show');
      }else{

        $('#nombre').val(data.nombre);
        $('#usuario').val(data.cwid);
        $('#rol').val(data.tipo);
        $('#email').val(data.correo);
        if(data.responsable == 0){
          $("#responsable").attr('checked', false);
        }
        else{
          $("#responsable").attr('checked', true);
        }

        $('#op').val(1);
        $("#usuario").attr('readonly',true);
      }
    },
    error:function(){
      $('#modal-error').modal('show');
    }
  });
}

</script>
